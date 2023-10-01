<?php

namespace App\Controller;

use App\Entity\Projects;
use App\Form\ProjectsType;
use App\Services\Uploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('admin/projects')]
class ProjectsController extends AbstractController
{
    #[Route('/add', name: 'projects.add')]
    public function index(
        Request $request,
        EntityManagerInterface $em,
        Uploader $uploader
    ): Response
    {
        $projects = new Projects();
        $repo = $em->getRepository(Projects::class);
        $allProjects = $repo->findAll();

        $form = $this->createForm(ProjectsType::class, $projects);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $uploadedFile = $form->get('image')->getData();
            dump($uploadedFile);
            if($uploadedFile != null)
            {
                $physicalPath = $this->getParameter('projects_image_directory');
    
                $newFileName = $uploader->uploadFile($uploadedFile, $physicalPath);
                
                $projects->setImage($newFileName);
            }

            $em->persist($projects);
            $em->flush();

            return $this->redirectToRoute('app_admin');
        }
        
        return $this->render('admin/projects.html.twig', [
            "list" => $allProjects,
            "form" => $form
        ]);
    }
}
