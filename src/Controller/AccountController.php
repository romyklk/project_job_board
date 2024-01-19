<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    #[Route('/account', name: 'app_account')]
    public function index(): Response
    {
        $user = $this->getUser();

        // Si l'utilisateur est une entreprise et qu'il a déjà un profil, on le redirige vers son profil
        if ($user->getRoles()[0] === 'ROLE_PRO' && $user->getEntrepriseProfil() !== null) {
            return $this->redirectToRoute('app_entreprise_profil_show', ['slug' => $user->getEntrepriseProfil()->getSlug()]);
        }
        // Si l'utilisateur est un candidat et qu'il a déjà un profil, on le redirige vers son profil
        if ($user->getRoles()[0] === 'ROLE_USER' && $user->getUserProfil() !== null) {
            return $this->redirectToRoute('app_user_profil_show', ['slug' => $user->getUserProfil()->getSlug()]);
        }

        return $this->render('account/index.html.twig');
    }
}
