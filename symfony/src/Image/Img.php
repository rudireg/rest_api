<?php
namespace App\Image;

/**
 * Class Img
 * @package App\Image
 */
class Img
{
    /**
     * Img constructor.
     * @param int $width
     * @param int $height
     * @param string $dir
     * @param string $path
     * @param string $ext
     */
    public function __construct(int $width, int $height, string $dir, string $path, string $ext)
    {
        $this->width  = $width;
        $this->height = $height;
        $this->dir    = $dir;
        $this->path   = $path;
        $this->ext    = $ext;
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @return string
     */
    public function getDir(): string
    {
        return $this->dir;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getExt(): string
    {
        return $this->ext;
    }

    /**
     * @var int
     */
    protected $width;
    /**
     * @var int
     */
    protected $height;
    /**
     * @var string
     */
    protected $dir;
    /**
     * @var string
     */
    protected $path;
    /**
     * @var string
     */
    protected $ext;
}