<?php

namespace App\Controller;

use App\Entity\EntrepriseProfil;
use App\Form\EntrepriseProfilType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/account')]
class EntrepriseProfilController extends AbstractController
{
    #[Route('/entreprise/profil', name: 'app_entreprise_profil')]
    public function index(): Response
    {
        $entrepriseProfil = new EntrepriseProfil();
        // Création du formulaire de profil entreprise
        $form = $this->createForm(EntrepriseProfilType::class, $entrepriseProfil);
        
        return $this->render('entreprise_profil/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
