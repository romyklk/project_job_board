<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Form\OfferFormType;
use App\Repository\ApplicationRepository;
use App\Repository\OfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// Ou #[is_granted('ROLE_PRO')] pour les professionnels
#[Route('/account')]
class OfferController extends AbstractController
{

    #[Route('/offer', name: 'app_offer')]
    public function index(OfferRepository $offerRepository): Response
    {

        $user = $this->getUser();

        $company = $user->getEntrepriseProfil();

        if (!$company) {
            return $this->redirectToRoute('app_entreprise_profil');
        }

        $Offers = $offerRepository->findByEntreprise($company);


        return $this->render('entreprise_profil/offer/index.html.twig', [
            'offers' => $Offers,
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
                ->addSuccess('Votre offre a bien été créée.');

            return $this->redirectToRoute('app_offer');
        }

        return $this->render('entreprise_profil/offer/create.html.twig', [
            'offerForm' => $form->createView(),
        ]);
    }

    #[Route('/entreprise/offer/show/{slug}', name: 'app_offer_show')]
    public function show(string $slug, OfferRepository $offerRepository,ApplicationRepository $applicationRepository): Response
    {
        $user = $this->getUser();

        $company = $user->getEntrepriseProfil();

        if (!$company) {
            return $this->redirectToRoute('app_entreprise_profil');
        }
        $offer = $offerRepository->findOneBy(['slug' => $slug]);

        if(!$offer){
            return $this->redirectToRoute('app_offer');
        }
        // Vérifier que l'offre appartient bien à l'entreprise connectée
        if ($offer->getEntreprise() !== $company) {
            return $this->redirectToRoute('app_offer');
        }

        // Récupartions des candidatures

        $applications = $applicationRepository->findBy(['offer' => $offer]);


        return $this->render('entreprise_profil/offer/show.html.twig', [
            'offer' => $offer,
            'applications' => $applications

        ]);
    }

    #[Route('/entreprise/offer/delete/{slug}', name: 'app_offer_delete')]
    public function delete(string $slug,  OfferRepository $offerRepository, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        $company = $user->getEntrepriseProfil();

        $offer = $offerRepository->findOneBy(['slug' => $slug]);
        if (!$offer) {
            return $this->redirectToRoute('app_offer');
        }
        // Vérifier que l'offre appartient bien à l'entreprise connectée
        if ($offer->getEntreprise() !== $company) {
            return $this->redirectToRoute('app_offer');
        }

        $em->remove($offer);
        $em->flush();
        notyf()->position('x', 'right')->position('y', 'top')->addSuccess('L\'offre a bien été supprimée.');
        return $this->redirectToRoute('app_offer');
    }

    #[Route('/entreprise/offer/edit/{slug}', name: 'app_offer_edit')]
    public function edit(string $slug,  OfferRepository $offerRepository, Request $request, EntityManagerInterface $em): Response
    {

        $offer = $offerRepository->findOneBy(['slug' => $slug]);
        $form = $this->createForm(OfferFormType::class, $offer);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            notyf()->position('x', 'right')->position('y', 'top')->addSuccess('L\'offre a bien été modifiée.');
            return $this->redirectToRoute('app_offer');
        }

        return $this->render('entreprise_profil/offer/edit.html.twig', ['offerForm' => $form->createView()]);
    }


}
