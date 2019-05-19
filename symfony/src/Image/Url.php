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
        $filePath = $fileDir . "src.$ext";
        file_put_contents($filePath, $file);
        return new Img(0, 0, $fileDir, $filePath, $ext);
    }

    /**
     * @var string
     */
    protected $project_dir;

}