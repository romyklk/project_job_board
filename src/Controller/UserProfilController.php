<?php

namespace App\Controller;

use App\Services\UploadFilesService;
use App\Entity\UserProfil;
use App\Form\UserProfilType;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/account')]
class UserProfilController extends AbstractController
{
    #[Route('/user/profil', name: 'app_user_profil')]
    public function index(Request $request,EntityManagerInterface $em, UploadFilesService $uploadFilesService): Response
    {
        $userProfil = new UserProfil();

        $form = $this->createForm(UserProfilType::class, $userProfil);

        $form->handleRequest($request);

        $slugify = new Slugify();

        if ($form->isSubmitted() && $form->isValid()) {
            // Je set le profil de l'utilisateur à l'utilisateur connecté
            $userProfil->setUser($this->getUser());
            
            // Je cré un slug à partir du nom et du prénom de l'utilisateur
            $userProfil->setSlug($slugify->slugify($userProfil->getFirstName() . ' ' . $userProfil->getLastName()));

            $file = $form['imageFile']->getData();

            $file_name = $uploadFilesService->saveFileUpload($file);

            $userProfil->setPicture($file_name);
            $em->persist($userProfil);
            $em->flush();
        }
        return $this->render('user_profil/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
