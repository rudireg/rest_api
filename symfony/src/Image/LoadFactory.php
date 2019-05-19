<?php
namespace App\Image;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class LoadFactory
 * @package App\Image
 */
class LoadFactory
{
    /**
     * LoadFactory constructor.
     * @param string $project_dir
     */
    public function __construct(string $project_dir){
        $this->project_dir = $project_dir;
    }

    /**
     * Получить загрузчик
     * @param $data
     * @return ILoad
     */
    public function getLoader($data): ILoad
    {
        if (is_array($data)) {
            return new Form($this->project_dir);
        } else if (substr($data, 0, 11) === "data:image/") {
            return new Json($this->project_dir);
        } else {
            return new Url($this->project_dir);
        }
    }

    /**
     * Подготовка данных для загрузки
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function prepare(Request $request): array
    {
        $dataSet = [];
        if (!empty($_FILES['userfile'])) {
            $dataSet[] = $_FILES['userfile'];
        } else if (!empty($_FILES['file']) && is_array($_FILES['file'])) {
            $len = count($_FILES['file']['tmp_name']);
            for ($i = 0; $i < $len; $i++) {
                $dataSet[] = [
                    'name'     => $_FILES['file']['name'][$i],
                    'type'     => $_FILES['file']['type'][$i],
                    'tmp_name' => $_FILES['file']['tmp_name'][$i],
                    'error'    => $_FILES['file']['error'][$i],
                    'size'     => $_FILES['file']['size'][$i]
                ];
            }
        } else if (!empty($url = $request->request->get('url'))) {
            $dataSet[] = $url;
        } else if (!empty($base64Data = file_get_contents('php://input'))) {
            $dataSet[] = $base64Data;
        } else {
            throw new \Exception('Empty data', 1);
        }
        return $dataSet;
    }

    /**
     * @var string
     */
    protected $project_dir;
}