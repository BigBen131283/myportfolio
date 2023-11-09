<?php

namespace App\Controller;

use App\Entity\Invitation;
use App\Form\InvitationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

#[Route('admin/invitation')]
class InvitationController extends AbstractController
{
    #[Route('/add', name: 'invitation.add')]
    public function addInvitation(
        Request $request,
        EntityManagerInterface $em
    ): Response
    {
        // 1. faire un formulaire
        // 2. renseigner un email, générer un uuid automatiquement, ne pas afficher le champ contributor
        $invitation = new Invitation();
        $repository = $em->getRepository(Invitation::class);
        $allInvitations = $repository->findAll();
        $form = $this->createForm(InvitationType::class, $invitation);
        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid())
        {
            $uuid = Uuid::v4();
            $invitation->setUuid($uuid);
            
            $em->persist($invitation);
            $em->flush();
            
            return $this->redirectToRoute('invitation.add'); 
        }
        
        return $this->render('admin/invitation.html.twig', [
            'invitations' => $allInvitations,
            'form' => $form
        ]);
    }
}
