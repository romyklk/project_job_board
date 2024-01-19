<?php

namespace App\Controller;

use Cocur\Slugify\Slugify;
use App\Entity\EntrepriseProfil;
use App\Form\EntrepriseProfilType;
use App\Repository\EntrepriseProfilRepository;
use App\Services\UploadFilesService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/account')]
class EntrepriseProfilController extends AbstractController
{
    // Création du profil entreprise
    #[Route('/entreprise/profil', name: 'app_entreprise_profil')]
    public function index(Request $request, UploadFilesService $uploadFilesService, EntityManagerInterface $em): Response
    {
        // Récupération de l'utilisateur connecté
        $user = $this->getUser();

        // Vérification si l'utilisateur a déjà un profil et le redirige vers son profil
        if ($user->getEntrepriseProfil()) {
            return $this->redirectToRoute('app_entreprise_profil_show', ['slug' => $user->getEntrepriseProfil()->getSlug()]);
        }


        $entrepriseProfil = new EntrepriseProfil();
        // Création du formulaire de profil entreprise
        $form = $this->createForm(EntrepriseProfilType::class, $entrepriseProfil);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entrepriseProfil->setUser($user);

            $slugify = new Slugify();

            $entrepriseProfil->setSlug($slugify->slugify($entrepriseProfil->getName()) . '-' . sha1($user->getId()));

            $file = $form['logoEntreprise']->getData();

            if ($file) {
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

    // Affichage du profil entreprise
    #[Route('/entreprise/profil/{slug}', name: 'app_entreprise_profil_show')]
    public function show(EntrepriseProfil $entrepriseProfil): Response
    {
        return $this->render('entreprise_profil/show.html.twig', [
            'entrepriseProfil' => $entrepriseProfil,
        ]);
    }

    // Modification du profil entreprise
    #[Route('/entreprise/profil/{slug}/edit', name: 'app_entreprise_profil_edit')]
    public function editEntrepriseProfil(string $slug,Request $request, EntrepriseProfilRepository $entrepriseProfilRepository,UploadFilesService $uploadFilesService,EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        $entrepriseProfil = $entrepriseProfilRepository->findOneBy(['slug' => $slug]);

        if (!$entrepriseProfil || $entrepriseProfil->getUser() !== $user) {
            return $this->redirectToRoute('app_entreprise_profil_show', ['slug' => $entrepriseProfil->getSlug()]);
        }

        $form = $this->createForm(EntrepriseProfilType::class, $entrepriseProfil);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $file = $form['logoEntreprise']->getData();

            if($file){

                $fileName = $uploadFilesService->updateFileUpload($file,$entrepriseProfil->getLogo());
                $entrepriseProfil->setLogo($fileName);
            }

            $em->flush();

            return $this->redirectToRoute('app_entreprise_profil_show', ['slug' => $entrepriseProfil->getSlug()]);
            
        }

        return $this->render('entreprise_profil/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
