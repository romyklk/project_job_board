<?php

namespace App\Controller;

use Cocur\Slugify\Slugify;
use App\Entity\EntrepriseProfil;
use App\Form\EntrepriseProfilType;
use App\Services\UploadFilesService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/account')]
class EntrepriseProfilController extends AbstractController
{
    #[Route('/entreprise/profil', name: 'app_entreprise_profil')]
    public function index(Request $request,UploadFilesService $uploadFilesService,EntityManagerInterface $em): Response
    {
        // Récupération de l'utilisateur connecté
        $user = $this->getUser();


        $entrepriseProfil = new EntrepriseProfil();
        // Création du formulaire de profil entreprise
        $form = $this->createForm(EntrepriseProfilType::class, $entrepriseProfil);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $entrepriseProfil->setUser($user);

            $slugify = new Slugify();

            $entrepriseProfil->setSlug($slugify->slugify($entrepriseProfil->getName()).'-'.sha1($user->getId()));

            $file = $form['logoEntreprise']->getData();

            if($file){
                $fileName = $uploadFilesService->saveFileUpload($file);
                $entrepriseProfil->setLogo($fileName);
            }

            $em->persist($entrepriseProfil);
            $em->flush();
        }
        
        return $this->render('entreprise_profil/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
