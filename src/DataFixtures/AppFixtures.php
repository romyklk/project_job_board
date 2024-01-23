<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Tag;
use App\Entity\User;
use App\Entity\Offer;
use App\Entity\UserProfil;
use App\Entity\HomeSetting;
use App\Entity\ContractType;
use App\Entity\EntrepriseProfil;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Fixtures pour le HomeSetting
        $tabImage = [
            'https://source.unsplash.com/random',
            'https://source.unsplash.com/user/wsanter',
            'https://source.unsplash.com/user/erondu',
            'https://source.unsplash.com/random/900×700/?fruit'
        ];

        $faker = Factory::create();

        for ($i = 0; $i <= 5; $i++) {
            $homeSetting = new HomeSetting();
            $homeSetting->setImage($faker->randomElement($tabImage))
                ->setMessage($faker->paragraph())
                ->setCallToAction($faker->word());
            $manager->persist($homeSetting);
        }


        // Insertion des tags
        $tabTags = [
            'PHP',
            'Symfony',
            'Javascript',
            'React',
            'Angular',
            'VueJS',
            'NodeJS',
            'Python',
            'Java',
            'C#',
            'C++',
            'Ruby',
            'HTML',
            'CSS',
            'SQL',
            'NoSQL',
            'MongoDB',
            'MySQL',
            'PostgreSQL',
            'Oracle',
            'MariaDB',
            'SQLite',
            'Git',
            'GitHub',
            'GitLab',
            'BitBucket',
            'Docker',
            'Kubernetes',
            'Linux',
            'Windows',
            'MacOS',
            'Android',
            'iOS',
            'AWS',
            'Azure',
            'Google Cloud',
            'Heroku',
            'Digital Ocean',
            'Vultr',
            'OVH',
            '1&1',
            'GoDaddy',
            'Namecheap'
        ];

        foreach ($tabTags as $tag) {
            $tagEntity = new Tag();
            $tagEntity->setName($tag);
            $manager->persist($tagEntity);
        }

        // Insertion des types de contractType

        $tabContractType = [
            'CDI',
            'CDD',
            'Freelance',
            'Stage',
            'Alternance',
            'Intérim',
        ];

        foreach ($tabContractType as $contractType) {
            $contractTypeEntity = new ContractType();
            $contractTypeEntity->setName($contractType);
            $manager->persist($contractTypeEntity);
        }

        // Insertion des utilisateurs

        $faker = Factory::create('fr_FR');
        $tabRoles = ['Candidat', 'Professionnel'];

        for ($i = 0; $i < 100; $i++) {
            $user = new User();
            $userRandomRole = $faker->randomElement($tabRoles);
            $user->setEmail($faker->email());
            $user->setPassword(password_hash('password', PASSWORD_DEFAULT));
            $user->setStatus($userRandomRole);
            if ($userRandomRole == 'Professionnel') {
                $user->setUsername($faker->company());
                $user->setRoles(['ROLE_PRO']);
            } else {
                $user->setUsername($faker->userName());
            }
            $manager->persist($user);
        }

        $manager->flush();


        // Insertion des userProfils

        $userProfils = $manager->getRepository(User::class)->findByStatus('Candidat');

        foreach ($userProfils as $userProfil) {
            $profil = new UserProfil();
            $profil->setUser($userProfil);
            $profil->setFirstName($faker->firstName());
            $profil->setLastName($faker->lastName());
            $profil->setSlug($faker->slug());
            $profil->setAddress($faker->streetAddress());
            $profil->setCity($faker->city());
            $profil->setZipCode($faker->postcode());
            $profil->setPhoneNumber($faker->phoneNumber());
            $profil->setCountry($faker->country());
            $profil->setJobSought($faker->jobTitle());
            $profil->setPresentation($faker->paragraph(mt_rand(1, 3)));
            $profil->setAvailability($faker->boolean());
            $profil->setWebsite($faker->url());
            $profil->setPicture('https://api.dicebear.com/7.x/initials/svg?seed=' . $userProfil->getUsername() . '&background=%23fff&color=%23fff');

            $manager->persist($profil);
        }

        // Création des entrepriseProfils

        $entrepriseProfils = $manager->getRepository(User::class)->findByStatus('Professionnel');

        foreach ($entrepriseProfils as $entrepriseProfil) {
            $newEntreprise = new EntrepriseProfil();
            $newEntreprise->setUser($entrepriseProfil)
                ->setSlug($faker->slug())
                ->setAddress($faker->streetAddress())
                ->setCity($faker->city())
                ->setZipCode($faker->postcode())
                ->setPhoneNumber($faker->phoneNumber())
                ->setCountry($faker->country())
                ->setWebsite($faker->url())
                ->setEmail($faker->email())
                ->setDescription($faker->paragraph(mt_rand(1, 3)))
                ->setName($faker->company())
                ->setLogo('https://api.dicebear.com/7.x/initials/svg?seed=' . $newEntreprise->getName() . '&background=%23fff&color=%23fff')
                ->setActivityArea($faker->jobTitle());
            $manager->persist($newEntreprise);
        }

        $manager->flush();

        // Insertion des offres d'emploi

        $recruteurs = $manager->getRepository(EntrepriseProfil::class)->findAll();
        $tags = $manager->getRepository(Tag::class)->findAll();
        $contractTypes = $manager->getRepository(ContractType::class)->findAll();

        
        for($i= 0 ; $i <=100 ; $i++){
            $offer = new Offer();
            $offer->setTitle($faker->jobTitle());
            $offer->setShortDescription($faker->word(mt_rand(100,255)));
            $offer->setContent($faker->paragraph(mt_rand(3, 6)));
            $offer->setSalary(mt_rand(30000, 100000));
            $offer->setLocation($faker->city());
            $offer->setContractType($faker->randomElement($contractTypes));
            $offer->setEntreprise($faker->randomElement($recruteurs));
            $randomTags = $faker->randomElements($tags, mt_rand(3, 8));

            foreach ($randomTags as $tag) {
                $offer->addTag($tag);
            }
            $manager->persist($offer);
        }

        $manager->flush();
    }
}