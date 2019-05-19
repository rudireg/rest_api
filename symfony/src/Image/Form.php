<?php
namespace App\Image;

/**
 * Class Form
 * @package App\Image
 */
class Form implements ILoad
{
    use Helper;

    /**
     * Form constructor.
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
        $filePath = NULL;
        if (!empty($data)) {
            $ext = explode('/', $data['type'])[1];
            if (!$this->isExtValid($ext)) {
                throw new UploadException(UploadException::UPLOAD_ERR_UNSUPPORTED_EXT);
            }
            $fileDir = $this->generateUploadDir($this->project_dir);
            $filePath = $fileDir . "src.$ext";
            move_uploaded_file($data['tmp_name'], $filePath);
            return new Img(0, 0, $fileDir, $filePath, $ext);
        }
        return NULL;
    }

    /**
     * @var string
     */
    private $project_dir;

}