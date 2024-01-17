<?php

namespace App\Controller;

use App\Entity\UserProfil;
use App\Form\UserProfilType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/account')]
class UserProfilController extends AbstractController
{
    #[Route('/user/profil', name: 'app_user_profil')]
    public function index(Request $request): Response
    {
        $userProfil = new UserProfil();
        $form = $this->createForm(UserProfilType::class, $userProfil);

        $form->handleRequest($request);
        return $this->render('user_profil/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
