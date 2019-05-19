<?php
namespace App\Controller;

use Exception;
use \App\Entity\Logs;
use \App\Image\Editor;
use \App\Image\Logger;
use \App\Image\Resize;
use \App\Entity\Images;
use \App\Image\LoadFactory;
use \App\Image\UploadException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Psr\Log\LoggerInterface;

/**
 * Class RestApiController
 * @package App\Controller
 */
class RestApiController extends AbstractController
{
    /**
     * Загрузка изображений
     * Matches /api/upload
     *
     * @Route("/api/upload", name="api_upload", methods={"POST"})
     *
     * @param Request $request
     * @param LoadFactory $factory
     * @param Resize $resize
     * @param LoggerInterface $logger
     * @param Editor $editor
     * @return JsonResponse
     * @throws Exception
     */
    public function upload(Request $request, LoadFactory $factory, Resize $resize, LoggerInterface $logger, Editor $editor)
    {
        switch ($request->getMethod()) {
            case 'POST':
                $rv = $dataSet = [];
                if (!empty($_FILES['userfile'])) {
                    $dataSet[] = $_FILES['userfile'];
                } else if (!empty($_FILES['file']) && is_array($_FILES['file'])) {
                    $len = count($_FILES['file']['tmp_name']);
                    for ($i = 0; $i < $len; $i++) {
                        $dataSet[] = [
                            'name' => $_FILES['file']['name'][$i],
                            'type' => $_FILES['file']['type'][$i],
                            'tmp_name' => $_FILES['file']['tmp_name'][$i],
                            'error' => $_FILES['file']['error'][$i],
                            'size' => $_FILES['file']['size'][$i]
                        ];
                    }
                } else if (!empty($url = $request->request->get('url'))) {
                    $dataSet[] = $url;
                } else if (!empty($base64Data = file_get_contents('php://input'))) {
                    $dataSet[] = $base64Data;
                } else {
                    return new JsonResponse(['error'=>1,'message'=>'Empty data']);
                }
                try {
                    foreach ($dataSet AS $data) {
                        // создаем загрузчика
                        $loader = $factory->getLoader($data);
                        // загружаем файл
                        $img = $loader->load($data);
                        // изменяем размеры + ведем логирование
                        $rv[] = $editor->process($img, $resize, $logger);
                    }
                } catch (\Exception $e) {
                    // save to DB
                    $logger->error(UploadException::codeToMessage($e->getCode()), ['code'=>$e->getCode()]);
                    return new JsonResponse(['error'=>$e->getCode(),'message'=>UploadException::codeToMessage($e->getCode())]);
                }
                return new JsonResponse(['success' => 1, 'images'=>$rv]);
                break;
        }
        return new JsonResponse(['error'=>1,'message'=>'Unsupported method']);
    }

    /**
     * Получение информации об изображении
     * Matches /api/get/{type}
     *
     * @Route("/api/get/{type}", name="api_getlist", methods={"GET"},  defaults={"type":"image"},
     *        requirements={
     *         "type": "image|error"
     *     })
     *
     * @param Request $request
     * @param string $type
     * @return JsonResponse
     */
    public function getList(Request $request, string $type)
    {
        if ($type === 'image') {
            $rep = $this->getDoctrine()->getRepository(Images::class);
        } else {
            $rep = $this->getDoctrine()->getRepository(Logs::class);
        }
        $res = $rep->createQueryBuilder('q')->getQuery()->getArrayResult();
        return new JsonResponse($res);
    }

    /**
     * Удалить изображение
     * Matches /api/delete/*
     *
     * @Route("/api/delete/{id}", name="api_delete", methods={"DELETE"}, defaults={"id":null},
     *     requirements={"id":"\d+"})
     *
     * @param Request $request
     * @param int $id
     * @param string $project_dir
     * @return JsonResponse
     */
    public function delete(Request $request, int $id, string $project_dir)
    {
        if (!$id) return new JsonResponse(['error'=>1,'message'=>'Id is empty']);
        $rep = $this->getDoctrine()->getRepository(Images::class);
        $img = $rep->findOneBy(['id'=>$id]);
        if (!empty($img)) {
            $em = $this->getDoctrine()->getManager();
            $filePath = $project_dir . $img->getUrl();
            unlink($filePath);
            $em->remove($img);
            $em->flush();
            return new JsonResponse(['success'=>1,'message'=>"Image id #$id is deleted"]);
        }
        return new JsonResponse(['error'=>1,'message'=>'Image not found']);
    }
}