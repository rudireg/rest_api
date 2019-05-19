<?php
namespace App\Controller;

use Exception;
use \App\Image\Logger;
use \App\Image\Resize;
use \App\Image\Editor;
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
     * Matches /api/upload/*
     *
     * @Route("/api/upload/{type}", name="api_upload", methods={"POST","GET","DELETE"},  defaults={"type":null})
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
            case 'GET':
                break;
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
                    $logger->error(UploadException::codeToMessage($e->getCode()), ['code'=>$e->getCode()]);
                    return new JsonResponse(['error'=>$e->getCode(),'message'=>UploadException::codeToMessage($e->getCode())]);
                }
                return new JsonResponse(['success' => 1, 'images'=>$rv]);
                break;
            case 'DELETE':
                break;
        }
    }
}