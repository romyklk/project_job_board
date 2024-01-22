<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Form\OfferFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

// Ou #[is_granted('ROLE_PRO')] pour les professionnels
#[Route('/account')]
class OfferController extends AbstractController
{
    #[Route('/offer', name: 'app_offer')]
    public function index(): Response
    {
        return $this->render('offer/index.html.twig', [
            'controller_name' => 'OfferController',
        ]);
    }

    //Cette route permet de créer une offre
    #[Route('/entreprise/offer/new', name: 'app_offer_create')]
    public function createNewOffer(Request $request, EntityManagerInterface $em): Response
    {
        // Verifier si l'entreprise est connectée a déjà un profil. Si oui on lui affiche le formulaire de création d'offre sinon on le redirige vers la création de profil

        $user = $this->getUser();
        $company = $user->getEntrepriseProfil();
        if (!$company) {
            return $this->redirectToRoute('app_entreprise_profil');
        }

        // Créer le formulaire d'offre et l'envoyer à la vue

        $offer = new Offer();

        $form = $this->createForm(OfferFormType::class, $offer);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $offer->setEntreprise($company);

            $em->persist($offer);
            $em->flush();

            notyf()
                ->position('x', 'right')
                ->position('y', 'top')
                ->duration(5000)
                ->dismissable(true)
                ->addSuccess('Votre offre a bien été créée.');
            
                return $this->redirectToRoute('app_offer');
        }

        return $this->render('offer/create.html.twig', [
            'offerForm' => $form->createView(),
        ]);
    }
}
