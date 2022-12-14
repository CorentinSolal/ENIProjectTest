<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('App\Entity\Campus')->findAll();
        return $this->render('home/index.html.twig', ['listeCampus' => $repo]);
    }
    /**
     * @Route("/home", name="app_home_bis")
     */
    public function indexBis(): Response
    {
        return $this->render('home/index.html.twig', []);
    }
}
