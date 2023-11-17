<?php

namespace App\Controller;

use App\Entity\Invitation;
use App\Entity\User;
use App\Form\InvitationType;
use App\Form\RegistrationFormType;
use App\Services\MailO2;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

class InvitationController extends AbstractController
{
    public function __construct(
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        private readonly EntityManagerInterface $em,
    )
    {}
    
    #[Route('admin/invitation/add', name: 'invitation.add')]
    public function addInvitation(
        Request $request,
        // MailO2 $mailer
    ): Response
    {
        // 1. faire un formulaire
        // 2. renseigner un email, générer un uuid automatiquement, ne pas afficher le champ contributor
        $invitation = new Invitation();
        $repository = $this->em->getRepository(Invitation::class);
        $allInvitations = $repository->findAll();
        $form = $this->createForm(InvitationType::class, $invitation);
        $form->handleRequest($request);
        // dd($allInvitations);
        // dd($_ENV['MAILER_DSN']);
        
        if($form->isSubmitted() && $form->isValid())
        {
            $uuid = Uuid::v4();
            $invitation->setUuid($uuid);
            
            $this->em->persist($invitation);
            $this->em->flush();

            // $mailer->sendEmail(
            //     'no-reply@big-ben.fr',
            //     $invitation->getEmail(),
            //     'Invitation',
            //     'Cliquer sur le lien ci-dessous pour finaliser votre inscription'
            // );
            
            return $this->redirectToRoute('invitation.add'); 
        }
        
        return $this->render('admin/invitation.html.twig', [
            'invitations' => $allInvitations,
            'form' => $form,
        ]);
    }

    #[Route('admin/invitation/delete/{id}', name: 'invitation.delete')]
    public function deleteInvitation(
        int $id,
        Invitation $invitation,
    ): Response
    {
        $repository = $this->em->getRepository(Invitation::class);
        $invitation = $repository->find($id);

        $repository->remove($invitation, true);
        
        return $this->redirectToRoute('invitation.add');
    }

    #[Route('invitation/{uuid}', name: 'invitation.register')]
    public function index(
        Invitation $invitation,
        Request $request,
    ): Response
    {

        if ($invitation->getContributor()!== null) {
            throw new Exception('This Invitation has already been used.');
        }

        $user = new User();
        $user->setEmail($invitation->getEmail());
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $user->setRoles($invitation->getRoles());
            // encode the plain password
            $user->setPassword(
                $this->userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $invitation->setContributor($user);

            $this->em->persist($user);
            $this->em->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_admin');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
