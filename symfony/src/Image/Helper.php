<?php
namespace App\Image;

/**
 * Trait Helper
 * @package App\Image
 */
trait Helper
{
    /**
     * @param string $ext
     * @return bool
     */
    public function isExtValid(string $ext): bool
    {
        return in_array($ext, ['jpg', 'jpeg', 'gif', 'png']);
    }

    /**
     * Генерирует путь к изображению
     * @param string $project_dir
     * @return string
     * @throws \Exception
     */
    public function generateUploadDir(string $project_dir): string
    {
        $date = new \DateTime();
        usleep(1);
        $md5 = md5($date->format('Y-m-d H:i:s:u').rand(1, 1000000));
        $folder = substr($md5, 0, 20);
        $dir = $project_dir . '/public/upload' . $date->format('/Y/m/d/') . $folder . '/';
        mkdir($dir, 0755, true);
        return $dir;
    }
}