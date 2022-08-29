<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Repository\LieuRepository;
use App\Entity\Lieu;
use App\Form\LieuType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/lieu")
 */
class LieuController extends AbstractController
{
    /**
     * @Route("/", name="app_lieu_index")
     */
    public function index(LieuRepository $lieuRepository): Response
    {
        
        
        return $this->render('lieu/index.html.twig', [
            'lieux' => $lieuRepository->findAll(),
        ]);
    }
    /**
     * @Route("/new", name="app_lieu_new", methods={"GET", "POST"})
     */
    public function new(Request $request, LieuRepository $lieuRepository,ManagerRegistry $doctrine): Response
    {
        $lieu = new Lieu();
        $form = $this->createForm(LieuType::class, $lieu);
        $form->handleRequest($request);
        $lieuRepository = $doctrine->getRepository(Lieu ::class);


        if ($form->isSubmitted() && $form->isValid()) {

            $lieuRepository->add($lieu, true);


            return $this->redirectToRoute('app_lieu', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('lieu/new.html.twig', [
            'lieu' => $lieu,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_lieu_delete", methods={"POST"})
     */
    public function delete(Request $request, Lieu $lieu, LieuRepository $lieuRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$lieu->getId(), $request->request->get('_token'))) {
            $lieuRepository->remove($lieu, true);
        }

        return $this->redirectToRoute('app_lieu', [], Response::HTTP_SEE_OTHER);
    }


    /**
     * @Route("/show/{id}", name="app_lieu_show",methods={"GET", "POST"})
     */
    public function show(Lieu $lieu): Response
    {


        return $this->render('lieu/show.html.twig', [
            'lieu' => $lieu,
        ]);
    }
}
