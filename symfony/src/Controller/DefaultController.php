<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends AbstractController
{
    /**
     * Matches /
     * @Route("/", name="index")
     * @return Response
     */
    public function index()
    {
        return $this->render('index.html.twig', []);
    }
}