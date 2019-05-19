<?php
namespace App\Image;

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
        } else if ($this->isJSON($data)) {
            return new Json($this->project_dir);
        } else {
            return new Url($this->project_dir);
        }
    }

    /**
     * Является ли строка JSON строкой
     * @param $string
     * @return bool
     */
    protected function isJSON ($string): bool {
        return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }

    /**
     * @var string
     */
    protected $project_dir;
}