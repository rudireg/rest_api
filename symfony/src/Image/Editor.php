<?php
namespace App\Image;

use Psr\Log\LoggerInterface;

/**
 * Class Editor
 * @package App\Image
 */
class Editor
{
    public function __construct()
    {
    }

    /**
     * @param Img $img
     * @param Resize $resize
     * @param LoggerInterface $logger
     * @return array
     */
    public function process(Img $img, Resize $resize, LoggerInterface $logger): array
    {
        $rv = [];
        // source data
        $info   = getimagesize($img->getPath());
        $width = $info[0];
        $height = $info[1];
        $rv[] = ['width'=>$width, 'height'=>$height, 'url'=>$img->getPath()];
        // save to DB
        $logger->log('', '', [
            'width'      => $width,
            'height'     => $height,
            'image_path' => $img->getPath(),
            'ext'        => $img->getExt()
        ]);

        // thumbnail 100x100
        $imagePath = $img->getDir()."thumbnail.".$img->getExt();
        $newSize = $resize->open($img->getPath())
            ->thumbnail(100, 100)
            ->save($imagePath);
        // save to DB
        $rv[] = ['width'=>$newSize['width'], 'height'=>$newSize['height'], 'url'=>$imagePath];
        $logger->log('', '', [
            'width'      => $newSize['width'],
            'height'     => $newSize['height'],
            'image_path' => $imagePath,
            'ext'        => $img->getExt()
        ]);

        // 500
        $imagePath = $img->getDir()."middle.".$img->getExt();
        $newSize = $resize->open($img->getPath())
            ->resize(500, 0)
            ->save($imagePath);
        // save to DB
        $rv[] = ['width'=>$newSize['width'], 'height'=>$newSize['height'], 'url'=>$imagePath];
        $logger->log('', '', [
            'width'      => $newSize['width'],
            'height'     => $newSize['height'],
            'image_path' => $imagePath,
            'ext'        => $img->getExt()
        ]);
        return $rv;
    }
}