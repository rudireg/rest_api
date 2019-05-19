<?php
namespace App\Image;

/**
 * Class Json
 * @package App\Image
 */
class Json implements ILoad
{
    /**
     * Json constructor.
     * @param string $project_dir
     */
    public function __construct(string $project_dir)
    {
        $this->project_dir = $project_dir;
    }

    public function load($data):? Img
    {
        // TODO: Implement load() method.
    }

    /**
     * @var string
     */
    protected $project_dir;
}