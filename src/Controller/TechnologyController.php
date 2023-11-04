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
        $repository = $em->getRepository(Technology::class);
        $allTechnology = $repository->findAll();

        $form = $this->createForm(TechnologyType::class, $technology, ['is_adding_form' => true]);
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
            'allTech' => $allTechnology,
            'form' => $form
        ]);
    }

    #[Route('/update/{id}', name:'technology.update')]
    public function updateTechnology(
        int $id,
        EntityManagerInterface $em,
        Request $request,
        Uploader $uploader
    ): Response
    {
        $repository = $em->getRepository(Technology::class);
        $technology = $repository->find($id);
        $allTechnology = $repository->findAll();

        $form = $this->createForm(TechnologyType::class, $technology, ['is_updating_form' => true]);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            
            $uploadedFile = $form->get('logo')->getData();

            if($uploadedFile){
                $currentFileName = $repository->find($id)->getLogo($technology);
                $physicalPath = $this->getParameter('logo_image_directory');
                $uploader->deletePreviousFile($physicalPath, $currentFileName);
                $newFileName = $uploader->uploadFile($uploadedFile, $physicalPath);

                $technology->setLogo($newFileName);
            }

            $em->persist($technology);
            $em->flush();
            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/technology.html.twig', [
            'allTech' => $allTechnology,
            'form' => $form
        ]);
    }

    #[Route('/delete/{id}', name:'technology.delete')]
    public function deleteTechnology(
        int $id,
        EntityManagerInterface $em,
        Uploader $uploader
    ): Response
    {
        $repository = $em->getRepository(Technology::class);
        $technology = $repository->find($id);
        $currentFileName = $technology->getLogo($technology);

        $uploader->deleteFile('images/logos/'.$currentFileName);
        $repository->remove($technology, true);

        return $this->redirectToRoute('app_admin');
    }
}

