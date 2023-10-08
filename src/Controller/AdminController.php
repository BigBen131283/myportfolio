<?php

namespace App\Controller;

use App\Entity\Projects;
use App\Entity\Technology;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(
        EntityManagerInterface $em
    ): Response
    {
        $allProjects = $em->getRepository(Projects::class);
        $allProjects->findAll();
        
        $allTech = $em->getRepository(Technology::class)
                      ->listTechnologies();

        $allUsers = $em->getRepository(User::class);
        $allUsers->findAll();
        
        return $this->render('admin/index.html.twig', [
            'allTech' => $allTech,
            'allProjects' => $allProjects,
            'allUsers' => $allUsers,
        ]);
    }
}
