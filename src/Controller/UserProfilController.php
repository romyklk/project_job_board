<?php

namespace App\Controller;

use App\Services\UploadFilesService;
use App\Entity\UserProfil;
use App\Form\UserProfilType;
use App\Repository\UserProfilRepository;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[Route('/account')]
class UserProfilController extends AbstractController
{
    // Création du profil utilisateur
    #[Route('/user/profil', name: 'app_user_profil')]
    public function index(Request $request, EntityManagerInterface $em, UploadFilesService $uploadFilesService): Response
    {
        // Vérification si l'utilisateur a déjà un profil et le redirige vers son profil

        $user = $this->getUser();
        if($user->getUserProfil()) {
            return $this->redirectToRoute('app_user_profil_show', ['slug' => $user->getUserProfil()->getSlug()]);
        }

        $userProfil = new UserProfil();

        $form = $this->createForm(UserProfilType::class, $userProfil);

        $form->handleRequest($request);

        $slugify = new Slugify();

        if ($form->isSubmitted() && $form->isValid()) {
            // Je set le profil de l'utilisateur à l'utilisateur connecté
            $userProfil->setUser($this->getUser());

            // Je cré un slug à partir du nom et du prénom de l'utilisateur
            $userProfil->setSlug($slugify->slugify(sha1($userProfil->getId()) . $userProfil->getFirstName() . ' ' . $userProfil->getLastName() . ' ' . $userProfil->getId()) . '' . uniqid());

            $file = $form['imageFile']->getData();

            if ($file) {
                $file_name = $uploadFilesService->saveFileUpload($file);
                $userProfil->setPicture($file_name);
            } else {
                $userProfil->setPicture('default.png');
            }

            $em->persist($userProfil);
            $em->flush();

            $this->addFlash('success', 'Votre profil a bien été créé !');

            return $this->redirectToRoute('app_user_profil_show', ['slug' => $userProfil->getSlug()]);
        }
        return $this->render('user_profil/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Affichage du profil utilisateur
    #[Route('/user/profil/{slug}', name: 'app_user_profil_show')]
    public function show(UserProfil $userProfil): Response
    {
        return $this->render('user_profil/show.html.twig', [
            'userProfil' => $userProfil,
        ]);
    }

    // Modification du profil utilisateur
    #[Route('/user/profil/{slug}/edit/', name: 'app_user_profil_edit')]
    public function editUserProfil(string $slug, Request $request, UserProfilRepository $userProfilRepository, UploadFilesService $uploadFilesService, EntityManagerInterface $em): Response
    {
        // Si l'utilisateur connecté n'est pas le propriétaire du profil, on le redirige vers son profil
        $user = $this->getUser();
        $userProfil = $userProfilRepository->findOneBy(['slug' => $slug]);

        if ($user !== $userProfil->getUser()) {
            return $this->redirectToRoute('app_user_profil_show', ['slug' => $userProfil->getSlug()]);
        }

        $form = $this->createForm(UserProfilType::class, $userProfil);

        $form->handleRequest($request);

        $slugify = new Slugify();
        if ($form->isSubmitted() && $form->isValid()) {
           $userProfil->setSlug(
                $slugify->slugify(sha1($userProfil->getId())
                        . $userProfil->getFirstName() . ' ' . $userProfil->getLastName() . ' ' . $userProfil->getId()) . '' . uniqid()
            ); 

        /*     $userProfil->setSlug(
                $slugify->slugify(sha1($userProfil->getId())
                    . sha1($userProfil->getFirstName()) . ' ' . sha1($userProfil->getLastName()) . ' ' . $userProfil->getId()) . '' . uniqid()
            ); */

            $file = $form['imageFile']->getData();

            if ($file) {
                $file_name = $uploadFilesService->updateFileUpload($file, $userProfil->getPicture());
                $userProfil->setPicture($file_name);
            }

            $em->flush();

            $this->addFlash('success', 'Votre profil a bien été modifié !');

            return $this->redirectToRoute('app_user_profil_show', ['slug' => $userProfil->getSlug()]);
        }


        return $this->render('user_profil/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Suppression du profil utilisateur
    #[Route('/user/profil/{slug}/delete', name: 'app_user_profil_delete')]
    public function deleteUserProfil(string $slug, UserProfilRepository $userProfilRepository,EntityManagerInterface $em,SessionInterface $session,TokenStorageInterface $tokenStorageInterface,UploadFilesService $uploadFilesService):Response
    {
        $user = $this->getUser();
        $userProfil = $userProfilRepository->findOneBy(['slug' => $slug]);
        // Si  l'utilisateur connecté n'est pas le propriétaire du profil, on le redirige vers son profil

        if($user !== $userProfil->getUser()) {
            return $this->redirectToRoute('app_user_profil_show', ['slug' => $userProfil->getSlug()]);
        }
        // Suppression de l'image du profil utilisateur
        $uploadFilesService->deleteFileUpload($userProfil->getPicture());
        
        $em->remove($userProfil);
        $em->flush();
        
        // tokenStorageInterface permet de déconnecter l'utilisateur et de supprimer son token de session
        $tokenStorageInterface->setToken(null);

        // On supprime la session(invalidation de la session)
        $session->invalidate();

        return $this->redirectToRoute('app_home');
    }
}
