<?php


namespace App\Test;


class TestClass
{
    /**
     * @var string
     */
    private $project_dir;

    public function __construct(string $project_dir)
    {
        $this->project_dir = $project_dir;
    }

    /**
     * @return string
     */
    public function getProjectDir(): string
    {
        return $this->project_dir;
    }

}