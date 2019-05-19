<?php
namespace App\Image;

/**
 * Class Url
 * @package App\Image
 */
class Url implements ILoad
{
    use Helper;

    /**
     * Url constructor.
     * @param string $project_dir
     */
    public function __construct(string $project_dir)
    {
        $this->project_dir = $project_dir;
    }

    /**
     * @param $data
     * @return Img|null
     * @throws \Exception
     */
    public function load($data):? Img
    {
        $data = mb_strtolower(trim($data));
        if (empty($data)) {
            throw new UploadException(UploadException::UPLOAD_ERR_UNSUPPORTED_EXT);
        }
        $fileDir = $this->generateUploadDir($this->project_dir);
        $file = file_get_contents($data);
        $ext = pathinfo(parse_url($data, PHP_URL_PATH), PATHINFO_EXTENSION);
        if (empty($ext) && !empty($http_response_header)) {
            $ext = $this->getExt($http_response_header);
        }
        $ext = !empty($ext)? $ext : 'jpg';
        $filePath = $fileDir . "src.$ext";
        file_put_contents($filePath, $file);
        return new Img(0, 0, $fileDir, $filePath, $ext);
    }

    /**
     * Определение типа изображения
     * @param $hrh
     * @return mixed|null
     */
    protected function getExt($hrh)
    {
        foreach ($hrh AS $h) {
          if (strpos($h, 'Content-Type: image/') !== false) {
              preg_match('/Content-Type: image\/(.*)/', $h, $matches);
              return !empty($matches[1])? $matches[1] : NULL;
          }
        };
        return NULL;
    }

    /**
     * @var string
     */
    protected $project_dir;

}