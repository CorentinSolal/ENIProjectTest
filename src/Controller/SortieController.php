<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Form\CampusType;
use App\Form\SortieType;
use App\Form\SortieFormType;
use App\Repository\CampusRepository;
use App\Repository\EtatRepository;
use App\Repository\LieuRepository;
use App\Repository\SortieRepository;
use App\Repository\VilleRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Sodium\add;

/**
 * @Route("/sortie")
 */
class SortieController extends AbstractController
{
    /**
     * @Route("/", name="app_sortie_index", methods={"GET", "POST"})
     */
    public function index(Request $request, ManagerRegistry $doctrine, SortieRepository $sortieRepository, CampusRepository $campusRepository, EtatRepository $etatRepository): Response
    {
        $form2 = $this->createFormBuilder()
            ->add('campus',TextType::class,['required' => false, 'attr'=>['class'=>'glass-button uk-margin', 'placeholder'=>'Campus']])
            ->add('nom',TextType::class,['required' => false, 'empty_data' => '','attr'=>['class'=>'glass-button uk-margin', 'placeholder'=>'Nom']])
            ->add('date1',DateTimeType::class,['required' => false, 'widget'=>'single_text','attr'=>['class' => 'glass-button uk-margin'],'data' => date_create('now')])
            ->add('date2',DateTimeType::class,['required' => false, 'widget'=>'single_text','attr'=>['class' => 'glass-button uk-margin'],'data' => date_create('now +1year')])
            ->add('organisateur',CheckboxType::class,['required' => false,'attr'=>['class'=>'uk-checkbox']])
            ->add('inscrit',CheckboxType::class,['required' => false,'attr'=>['class'=>'uk-checkbox'],'data'=> true])
            ->add('non_inscrit',CheckboxType::class,['required' => false,'attr'=>['class'=>'uk-checkbox'],'data'=> true])
            ->add('passees',CheckboxType::class,['required' => false,'attr'=>['class'=>'uk-checkbox']])
            ->add('rechercher', SubmitType::class,['attr'=>['class'=>'glass-button uk-margin']])
            ->add('reset', SubmitType::class, ['label' => 'reset','attr'=>['class'=>'glass-button uk-margin']])

            ->getForm();
        $session = $request->getSession();
        $form2->handleRequest($request);

        $id =$this->getUser()->getUserIdentifier();
        $repoParticipant = $doctrine->getRepository(Participant ::class);
        $mecConnecte = $repoParticipant->findOneBy(['mail' => $id ]);

        if ($form2->isSubmitted() && $form2->isValid()  && !$form2->get('reset')->isClicked()) {

            $retour_form = $form2->getViewData();
            $nom_campus = $retour_form["campus"];
            $campus = $campusRepository->findOneBy(["nom" => $nom_campus]);
            $nom =  $retour_form["nom"];
            $date1 =  $retour_form["date1"];
            $date2 =  $retour_form["date2"];
            $orga =  $retour_form["organisateur"];
            $inscrit =  $retour_form["inscrit"];
            $non_inscrit =  $retour_form["non_inscrit"];
            $passees =  $retour_form["passees"];

            $entityManager = $doctrine->getManager();
            $dql = "Select s from App\Entity\Sortie s
            where s.campus = :campus
            and s.nom like :nom AND s.dateHeureDebut >= :date1
            and s.dateHeureDebut <= :date2";

            if ($orga){
                $dql .= " and s.organisateur = :mecConnecte1";}

            if (!$inscrit){
                $dql .= " and :mecConnecte2 not member of s.participantList";}

            if (!$non_inscrit){
                $dql .= " and :mecConnecte3 member of s.participantList";}

            if (!$passees){
                $etat = $etatRepository->findBy(['id' => [1,2,3,4]]);
                $dql .= " and s.etat in (:valEtat)";}

            $query = $entityManager->createQuery($dql);
            $query->setParameter('campus', $campus);
            $query->setParameter('nom', '%'.$nom.'%');
            $query->setParameter('date1', $date1);
            $query->setParameter('date2', $date2);
            !$orga ? :$query->setParameter('mecConnecte1', $mecConnecte);
            $inscrit ? :$query->setParameter('mecConnecte2', $mecConnecte);
            $non_inscrit ? :$query->setParameter('mecConnecte3', $mecConnecte);
            $passees ? :$query->setParameter('valEtat', $etat);

            $sorties = $query->getResult();




            return $this->renderForm('sortie/index.html.twig', [
                'sorties' => $sorties,
                'form2' => $form2,
                'mecConnecte' => $mecConnecte,
            ]);
        }


        return $this->renderForm('sortie/index.html.twig', [
            'sorties' => $sortieRepository->findAll(),
            'form2' => $form2,
            'mecConnecte' => $mecConnecte,
        ]);
    }

    /**
     * @Route("/new", name="app_sortie_new", methods={"GET", "POST"})
     */
    public function new(Request $request, SortieRepository $sortieRepository,ManagerRegistry $doctrine): Response
    {
        $sortie = new Sortie();
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);
        $session = $request->getSession();
        //$id = $session->getId();
        $id =$this->getUser()->getUserIdentifier();
        $repoParticipant = $doctrine->getRepository(Participant ::class);
        //$organisateur = $repoParticipant->find($id);
        $organisateur = $repoParticipant->findOneBy(['mail' => $id ]);

        if ($form->isSubmitted() && $form->isValid()) {
            $sortie->setOrganisateur($organisateur);
            $sortie->addParticipantList($organisateur);
            $sortieRepository->add($sortie, true);


            return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sortie/new.html.twig', [
            'sortie' => $sortie,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/show/{id}", name="app_sortie_show", methods={"GET"})
     */
    public function show(Sortie $sortie): Response
    {
        return $this->render('sortie/show.html.twig', [
            'sortie' => $sortie,
        ]);
    }

    /**
     * @Route("/add/{id}", name="app_sortie_ajout", methods={"GET", "POST"})
     */
    public function ajout(Request $request, SortieRepository $sortieRepository,ManagerRegistry $doctrine, Sortie $sortie): Response
    {
        $sortieCourante = $sortieRepository->findOneBy(['id'=>$sortie]);
        $listeParticipant = $sortieCourante->getParticipantList();
        $participantRestant = count($listeParticipant)-$sortieCourante->getNbInscriptionsMax();
        $id =$this->getUser()->getUserIdentifier();
        //$session = $request->getSession();
        //$id = $session->getId();
        //dd($id);
        $repoParticipant = $doctrine->getRepository(Participant ::class);
        $nouvParticipant = $repoParticipant->findOneBy(['mail' => $id ]);

        $sortie->addParticipantList($nouvParticipant);
        $sortieRepository->add($sortie, true);

        return $this->render('sortie/show.html.twig', [
            'sortie' => $sortie,
            'participantRestant' => $participantRestant
        ]);

    }

    /**
     * @Route("/suppr/{id}", name="app_sortie_suppr", methods={"GET", "POST"})
     */
    public function suppression(Request $request, SortieRepository $sortieRepository,ManagerRegistry $doctrine, Sortie $sortie): Response
    {
        $id =$this->getUser()->getUserIdentifier();
        //$session = $request->getSession();
        //$id = $session->getId();
        //dd($id);
        $repoParticipant = $doctrine->getRepository(Participant ::class);
        $ancParticipant = $repoParticipant->findOneBy(['mail' => $id ]);

        $sortie->removeParticipantList($ancParticipant);
        $sortieRepository->add($sortie, true);
        return $this->render('sortie/show.html.twig', [
            'sortie' => $sortie,
        ]);

    }

    /**
     * @Route("/publier/{id}", name="app_sortie_publier", methods={"GET", "POST"})
     */
    public function publication(Request $request, SortieRepository $sortieRepository,ManagerRegistry $doctrine, Sortie $sortie, EtatRepository $etatRepository): Response
    {
        $id =$this->getUser()->getUserIdentifier();
        $repoParticipant = $doctrine->getRepository(Participant ::class);
        $mecConnecte = $repoParticipant->findOneBy(['mail' => $id ]);

        if ($mecConnecte ==$sortie->getOrganisateur()) {
            $id = $this->getUser()->getUserIdentifier();
            //$session = $request->getSession();
            //$id = $session->getId();
            //dd($id);
            $repoParticipant = $doctrine->getRepository(Participant ::class);
            $ancParticipant = $repoParticipant->findOneBy(['mail' => $id]);

            $sortie->setEtat($etatRepository->findOneBy(['id' => 2]));
            $sortieRepository->add($sortie, true);
            return $this->render('sortie/show.html.twig', [
                'sortie' => $sortie,
            ]);
        }
        $session = $request->getSession();
        $session->getFlashBag()->add('notice', "Tu n'as pas le droit de faire cette publication chouchou");
        return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);

    }

    /**
     * @Route("/edit/{id}", name="app_sortie_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Sortie $sortie, ManagerRegistry $doctrine, SortieRepository $sortieRepository): Response
    {
        $id =$this->getUser()->getUserIdentifier();
        $repoParticipant = $doctrine->getRepository(Participant ::class);
        $mecConnecte = $repoParticipant->findOneBy(['mail' => $id ]);

        if ($mecConnecte ==$sortie->getOrganisateur()) {
            $form = $this->createForm(SortieType::class, $sortie);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $sortieRepository->add($sortie, true);

                return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('sortie/edit.html.twig', [
                'sortie' => $sortie,
                'form' => $form,
            ]);
        }
        $session = $request->getSession();
        $session->getFlashBag()->add('notice', "Tu n'as pas le droit de faire cette modification co*");
        return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{id}", name="app_sortie_delete", methods={"POST"})
     */
    public function delete(Request $request, Sortie $sortie, SortieRepository $sortieRepository, ManagerRegistry $doctrine, EtatRepository $etatrepository): Response
    {
        $id =$this->getUser()->getUserIdentifier();
        $repoParticipant = $doctrine->getRepository(Participant ::class);
        $mecConnecte = $repoParticipant->findOneBy(['mail' => $id ]);

        if ($mecConnecte ==$sortie->getOrganisateur()) {
            if ($this->isCsrfTokenValid('delete' . $sortie->getId(), $request->request->get('_token'))) {
                $sortie->setEtat($etatrepository->findOneBy(['libelle' => 'annulée']));

            }
            //dd($sortie);
            $entityManager = $doctrine->getManager();
            $dql = "Update s from App\Entity\Sortie s
            set s.etat_id = :etat
            where s.id = :id;";
            $query = $entityManager->createQuery($dql);
            $query->setParameter('etat', $sortie->getEtat()->getId());
            $query->setParameter('id', $sortie->getId());

            $entityManager->persist($sortie);
            $entityManager->flush();

            return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);
        }
        $session = $request->getSession();
        $session->getFlashBag()->add('notice', "Tu n'as pas le droit de faire cette suppression co*");
        return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);

    }

    /**
     * @Route("/ville/{ville}", name="app_sortie_par_ville", methods={"GET"})
     */
    public function voirParVille(SortieRepository $sortieRepository, VilleRepository $villeRepository, LieuRepository $lieuRepository , $ville): Response
    {
        $ville_selec = $villeRepository->findOneBy(['nom' => $ville]);
        $lieux_selec = $lieuRepository->findBy(['ville' => $ville_selec]);
        $ens_sortie=[];
        foreach ($lieux_selec as $lieu) {
            $des_sorties =$sortieRepository->findBy(['lieu'=> $lieu]);
            foreach ($des_sorties as  $uneSortie) {
                array_push($ens_sortie , $uneSortie);
            }

        }
        //dd($lieux_selec);
        return $this->render('sortie/index.html.twig', [
            'sorties' =>$ens_sortie,
        ]);
    }

    /**
     * @Route("/campus/{campus}", name="app_sortie_par_campus", methods={"GET"})
     */
    public function voirParCampus(SortieRepository $sortieRepository, CampusRepository $campusRepository,  $campus): Response
    {
        $campus_selec = $campusRepository->findOneBy(['nom' => $campus]);
        $ens_sortie=$sortieRepository->findBy(['campus' => $campus_selec]);
        //dd($lieux_selec);
        return $this->render('sortie/index.html.twig', [
            'sorties' =>$ens_sortie,
        ]);
    }


}
