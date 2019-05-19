<?php
namespace App\Image;

/**
 * Class Resize
 * @package App\Image
 */
class Resize
{
    public function __construct()
    {
    }

    /**
     * Открыть изображение
     * @param string $file
     * @return Resize
     */
    public function open(string $file)
    {
        if (!file_exists($file)) {
            throw new \InvalidArgumentException("$file not found.");
        }
        $this->info   = getimagesize($file);
        $this->width  = $this->info[0];
        $this->height = $this->info[1];
        $this->type   = $this->info[2];
        switch ($this->type) {
            case 1:
                $this->img = imageCreateFromGif($file);
                imageSaveAlpha($this->img, true);
                break;
            case 2:
                $this->img = imageCreateFromJpeg($file);
                break;
            case 3:
                $this->img = imageCreateFromPng($file);
                imageSaveAlpha($this->img, true);
                break;
        }
        return $this;
    }

    /**
     * Создание привью
     * @param $th
     * @param $tw
     * @return $this
     */
    function thumbnail($th, $tw)
    {
        $w = $this->width;
        $h = $this->height;
        if ($w < $h) {
            $this->resize($h, $h);
        } else {
            $this->resize($w, $w);
        }
        $this->resize($tw, $th);
        return $this;
    }

    /**
     * Сохранение изображения в файл на сервере.
     * @param string $src - Путь куда сохранить файл (путь включает и имя файла)
     * @return array
     */
    public function save(string $src): array
    {
        switch ($this->type) {
            case 1:
                imageGif($this->img, $src);
                break;
            case 2:
                imageJpeg($this->img, $src, 100);
                break;
            case 3:
                imagePng($this->img, $src);
                break;
        }
        $rv = [
            'width'  => imagesx($this->img),
            'height' => imagesy($this->img)
        ];
        imagedestroy($this->img);
        return $rv;
    }

    /**
     * Уменьшает или увеличивает изображение не искажая его пропорци
     * @param int $w - новая ширина изображения
     * @param int $h - новая высота изображения
     * @return Resize
     */
    public function resize(int $w=0, int $h=0): self
    {
        if (empty($w)) {
            $w = ceil($h / ($this->height / $this->width));
        }
        if (empty($h)) {
            $h = ceil($w / ($this->width / $this->height));
        }
        $tmp = imageCreateTrueColor($w, $h);
        if ($this->type == 1 || $this->type == 3) {
            imagealphablending($tmp, true);
            imageSaveAlpha($tmp, true);
            $transparent = imagecolorallocatealpha($tmp, 0, 0, 0, 127);
            imagefill($tmp, 0, 0, $transparent);
            imagecolortransparent($tmp, $transparent);
        }
        $tw = ceil($h / ($this->height / $this->width));
        $th = ceil($w / ($this->width / $this->height));
        if ($tw < $w) {
            imageCopyResampled($tmp, $this->img, ceil(($w - $tw) / 2), 0, 0, 0, $tw, $h, $this->width, $this->height);
        } else {
            imageCopyResampled($tmp, $this->img, 0, ceil(($h - $th) / 2), 0, 0, $w, $th, $this->width, $this->height);
        }
        $this->img = $tmp;
        return $this;
    }

    /**
     * Оптимизация изображения
     * @param int $size - размер изображения
     * @param int $w - максимальная ширина
     * @param int $h - максимальная высота
     * @return Resize
     */
    public function optimize(int $size, int $w=1800, int $h=1000): self
    {
        if ($this->width > $w) {
            $this->resize($w, 0);
        } elseif ($this->height > $h) {
            $this->resize(0, $h);
        } elseif ($size >= 1000000) { // если больше или ровно 1 Мб
            $this->resize($this->width-1, 0);
        }
        return $this;
    }

    /**
     * Обрезать изображение.
     * Вырезает из исходного изображения часть размером $w на $h.
     * $x и $y задают начальные координаты в пикселях или процентах.
     * @param int $w - ширина вырезаной детали
     * @param int $h - высота вырезаной детали
     * @param string $x - начальные координаты в пикселях или процентах
     * @param string $y - начальные координаты в пикселях или процентах
     * @return Resize
     */
    public function crop(int $w, int $h, string $x='100%', string $y='100%'): self
    {
        if (strpos($x, '%') !== false) {
            $x = intval($x);
            $x = ceil(($this->width * $x / 100) - ($w / 100 * $x));
        }
        if (strpos($y, '%') !== false) {
            $y = intval($y);
            $y = ceil(($this->height * $y / 100) - ($h / 100 * $y));
        }
        $tmp = imageCreateTrueColor($w, $h);
        if ($this->type == 1 || $this->type == 3) {
            imagealphablending($tmp, true);
            imageSaveAlpha($tmp, true);
            $transparent = imagecolorallocatealpha($tmp, 0, 0, 0, 127);
            imagefill($tmp, 0, 0, $transparent);
            imagecolortransparent($tmp, $transparent);
        }
        imageCopyResampled($tmp, $this->img, 0, 0, $x, $y, $this->width, $this->height, $this->width, $this->height);
        $this->img = $tmp;
        return $this;
    }

    protected $img;
    protected $info;
    protected $width;
    protected $height;
    protected $type;
}