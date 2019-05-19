<?php
namespace App\Image;

/**
 * Class Json
 * @package App\Image
 */
class Json implements ILoad
{
    use Helper;

    /**
     * Json constructor.
     * @param string $project_dir
     */
    public function __construct(string $project_dir)
    {
        $this->project_dir = $project_dir;
    }

    /**
     * @param $data
     * @return Img|null
     * @throws UploadException
     */
    public function load($data):? Img
    {
        try {
            $pos = strpos($data, 'base64');
            $type = substr($data,0,$pos+strlen('base64'));
            preg_match('/image\/(.*);base64/', $type, $matches);
            $ext = $matches[1];
            $base64String = substr($data, $pos+strlen('base64'));
            $base64String = str_replace(' ', '+', $base64String);
            $decoded = base64_decode($base64String);

            $fileDir = $this->generateUploadDir($this->project_dir);
            $filePath = $fileDir . "src.$ext";
            file_put_contents($filePath, $decoded);
            return new Img(0, 0, $fileDir, $filePath, $ext);
        } catch (\Exception $e) {
            throw new UploadException(UploadException::UPLOAD_ERR_UNSUPPORTED_EXT);
        }
    }

    /**
     * @var string
     */
    protected $project_dir;
}