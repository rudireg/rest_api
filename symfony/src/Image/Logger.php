<?php
namespace App\Image;

use App\Entity\Images;
use App\Entity\Logs;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class Logger
 * @package App\Image
 */
class Logger implements LoggerInterface
{
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function addImage(int $width, int $height, string $url, string $ext)
    {

    }

    public function addError(int $errorCode, string $errorMsg)
    {

    }

    public function emergency($message, array $context = array())
    {
        // TODO: Implement emergency() method.
    }

    public function alert($message, array $context = array())
    {
        // TODO: Implement alert() method.
    }

    public function critical($message, array $context = array())
    {
        // TODO: Implement critical() method.
    }

    /**
     * @param string $message
     * @param array $context
     * @throws \Exception
     */
    public function error($message, array $context = array())
    {
        $log = new Logs();
        $log->setErrorCode($context['code']);
        $log->setErrorMsg($message);
        $log->setDate((new \DateTime()));
        $this->em->persist($log);
        $this->em->flush();
    }

    public function warning($message, array $context = array())
    {
        // TODO: Implement warning() method.
    }

    public function notice($message, array $context = array())
    {
        // TODO: Implement notice() method.
    }

    public function info($message, array $context = array())
    {
        // TODO: Implement info() method.
    }

    public function debug($message, array $context = array())
    {
        // TODO: Implement debug() method.
    }

    /**
     * @param mixed $level
     * @param string $message
     * @param array $context
     */
    public function log($level, $message, array $context = array())
    {
        $image = new Images();
        $image->setWidth($context['width']);
        $image->setHeight($context['height']);
        $image->setExt($context['ext']);
        $pos = strpos($context['image_path'], '/public/');
        $url = substr($context['image_path'], $pos);
        $image->setUrl($url);
        $this->em->persist($image);
        $this->em->flush();
    }

    /**
     * @var EntityManagerInterface
     */
    protected $em;
}


