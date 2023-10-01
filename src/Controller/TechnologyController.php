<?php

namespace App\Controller;

use App\Entity\Technology;
use App\Form\TechnologyType;
use App\Services\Uploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('admin/technology')]
class TechnologyController extends AbstractController
{
    #[Route('/add', name: 'technology.add')]
    public function addTechnology(
        Request $request,
        EntityManagerInterface $em,
        Uploader $uploader
    ): Response
    {
        $technology = new Technology();
        $repo = $em->getRepository(Technology::class);
        $allTechnology = $repo->findAll();

        $form = $this->createForm(TechnologyType::class, $technology);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $uploadedFile = $form->get('logo')->getData();
            $physicalPath = $this->getParameter('logo_image_directory');

            $newFileName = $uploader->uploadFile($uploadedFile, $physicalPath);
            
            $technology->setLogo($newFileName);

            $em->persist($technology);
            $em->flush();

            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/technology.html.twig', [
            'list' => $allTechnology,
            'form' => $form
        ]);
    }
}

