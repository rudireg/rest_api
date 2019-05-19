<?php
namespace App\Image;

/**
 * Interface ILoad
 * @package App\Image
 */
interface ILoad
{
    /**
     * Загрузить файл
     * @param $data
     * @return Img
     */
    public function load($data):? Img;
}