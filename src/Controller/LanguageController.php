<?php

namespace App\Controller;

use App\Entity\Language;
use App\Form\LanguageType;
use App\Services\Uploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('admin/language')]
class LanguageController extends AbstractController
{
    #[Route('/add', name: 'language.add')]
    public function addLanguage(
        Request $request,
        EntityManagerInterface $em,
        Uploader $uploader
    ): Response
    {
        $language = new Language();
        $repo = $em->getRepository(Language::class);
        $allLanguages = $repo->findAll();

        $form = $this->createForm(LanguageType::class, $language);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $uploadedFile = $form->get('logo')->getData();
            $physicalPath = $this->getParameter('logo_image_directory');

            $newFileName = $uploader->uploadFile($uploadedFile, $physicalPath);
            
            $language->setLogo($newFileName);

            $em->persist($language);
            $em->flush();

            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/language.html.twig', [
            'list' => $allLanguages,
            'form' => $form
        ]);
    }
}
