<?php
namespace App\Command;

use App\Test\TestClass;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestCommand extends Command
{
    /**
     * @var TestClass
     */
    private $test;

    public function __construct(TestClass $test)
    {
        parent::__construct();
        $this->test = $test;
    }

    protected function configure()
    {
        $this->setName('app:test');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln($this->test->getProjectDir());

    }
}