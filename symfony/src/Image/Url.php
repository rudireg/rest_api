<?php
namespace App\Image;

/**
 * Class Url
 * @package App\Image
 */
class Url implements ILoad
{
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