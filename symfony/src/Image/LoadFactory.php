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
        } else if (substr($data, 0, 11) === "data:image/") {
            return new Json($this->project_dir);
        } else {
            return new Url($this->project_dir);
        }
    }

    /**
     * @var string
     */
    protected $project_dir;
}