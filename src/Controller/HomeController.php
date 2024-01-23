<?php

namespace App\Controller;

use App\Entity\Application;
use App\Entity\HomeSetting;
use App\Form\ApplicationType;
use App\Entity\EntrepriseProfil;
use App\Repository\ApplicationRepository;
use App\Repository\TagRepository;
use App\Repository\OfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\HomeSettingRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EntityManagerInterface $em, HomeSettingRepository $homeSettingRepository, OfferRepository $offerRepository): Response
    {
        // Récupération du HomeSetting

        //$settings = $em->getRepository(HomeSetting::class)->findAll();

        $settings = $homeSettingRepository->findAll();

        // Récupération des 6 dernières offres

        $offers = $offerRepository->findBy([], ['id' => 'DESC'], 6);

        // Récupération des 4 dernières entreprises
        $entreprises = $em->getRepository(EntrepriseProfil::class)->findBy([], ['id' => 'DESC'], 4);

        return $this->render('home/index.html.twig', [
            'settings' => $settings,
            'offers' => $offers,
            'entreprises' => $entreprises
        ]);
    }

    #[Route('/offre-emploi', name: 'app_offre_emploi')]
    public function offreEmploi(OfferRepository $offerRepository, TagRepository $tagRepository): Response
    {
        $offers = $offerRepository->findBy([], ['id' => 'DESC']);

        $tags = $tagRepository->findAll();

        return $this->render('home/offre_emploi.html.twig', [
            'offers' => $offers,
            'tags' => $tags
        ]);
    }

    #[Route('/offre-emploi/{slug}', name: 'app_offre_emploi_show')]
    public function offreEmploiShow($slug, OfferRepository $offerRepository, ApplicationRepository $applicationRepository,EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
       
        $offer = $offerRepository->findOneBy(['slug' => $slug]);

        if (!$offer) {
            throw $this->createNotFoundException("L'offre demandée n'existe pas");
        }

        $entreprise = $offer->getEntreprise();

        $existingsApplication = $applicationRepository->findOneBy(
            ['offer' => $offer, 'user' => $user,'entreprise' => $entreprise],

        );

        if ($existingsApplication) {
            notyf()
            ->position('x', 'right')
            ->position('y', 'top')
            ->addWarning('Vous avez déjà postulé à cette offre');
        }

        
        $application = new Application();
        $form = $this->createForm(ApplicationType::class, $application);
        
        if($form->isSubmitted() && $form->isValid()){
            dd($form->getData());
            $application->setUser($user);
            $application->setOffer($offer);
            $application->setCreatedAt(new \DateTimeImmutable());
            $application->setMessage($form->get('message')->getData());
            $application->setStatus('STATUS_PENDING');
            $em->persist($application);
            $em->flush();
        }

        return $this->render('home/offre_emploi_show.html.twig', [
            'offer' => $offer,
            'form' => $form->createView(),
            'existingsApplication' => $existingsApplication
        ]);
    }
}
