<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ParticipantType;
use App\Repository\CampusRepository;
use App\Repository\ParticipantRepository;
use App\Security\AppAuthAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use function App\Fonctions\ecritureObjets;
use function App\Fonctions\lectureCsv;



/**
 *
 * @Route("/participant")
 */
class ParticipantController extends AbstractController
{
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/", name="app_participant_index", methods={"GET", "POST"})
     */
    public function index(ParticipantRepository $participantRepository, UserPasswordHasherInterface $userPasswordHasher,  CampusRepository $campusRepository,Request $request): Response
    {
        $form3 = $this->createFormBuilder()
            ->add('ficcsv',FileType::class,['label' => 'Fichier des participants', 'mapped' => false, 'required' => false,'constraints' => [new File(['mimeTypes' => ['text/csv','text/plain']])], 'attr'=>['class'=>'glass-button uk-margin']])
            ->add('envoyer', SubmitType::class,['attr'=>['class'=>'glass-button uk-margin']])
            ->getForm();

        $session = $request->getSession();
        $form3->handleRequest($request);
        if ($form3->isSubmitted() && $form3->isValid()) {

            $ficCsv = $form3->get("ficcsv")->getData();
            $donnees = lectureCsv($ficCsv);
            //dd($donnees);
            $nouvParti = ecritureObjets($donnees, $campusRepository);
            foreach ($nouvParti as $participant){
                $participant->setPassword(
                    $userPasswordHasher->hashPassword(
                        $participant, $participant->getPassword()
                    )
                );
                $participantRepository->add($participant, true);
            }
        }


        return $this->render('participant/index.html.twig', [
            'participants' => $participantRepository->findAll(),
            'form' => $form3->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_participant_show", methods={"GET"})
     */
    public function show(Participant $participant): Response
    {
        return $this->render('participant/show.html.twig', [
            'participant' => $participant,
            'image' => 'assets/images/profil/'.$participant->getImage(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_participant_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Participant $participant, ParticipantRepository $participantRepository, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ParticipantType::class, $participant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $participant->setPassword(
                $userPasswordHasher->hashPassword($participant, $form->get('password')->getData())
            );
            $participantRepository->add($participant, true);

            return $this->redirectToRoute('app_home',[],Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('participant/edit.html.twig', ['participant' => $participant, 'form' => $form,]);
    }

    /**
     * @Route("/{id}", name="app_participant_delete", methods={"POST"})
     */
    public function delete(Request $request, Participant $participant, ParticipantRepository $participantRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$participant->getId(), $request->request->get('_token'))) {
            $participantRepository->remove($participant, true);
        }

        return $this->redirectToRoute('app_participant_index', [], Response::HTTP_SEE_OTHER);
    }

}
