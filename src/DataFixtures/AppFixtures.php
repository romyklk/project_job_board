<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Tag;
use App\Entity\User;
use App\Entity\Offer;
use DateTimeImmutable;
use App\Entity\UserProfil;
use App\Entity\Application;
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

        for ($i = 0; $i <= 2; $i++) {
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
            'Namecheap',
            'Cloudflare',
            'Netlify',
            'Vercel',
            'Firebase',
            'Algolia',
            'Elasticsearch',
            'Redis',
            'RabbitMQ',
            'Kafka',
            'Nginx',
            'Apache',
            'IIS',
            'Ansible',
            'Terraform',
            'Puppet',
            'Jenkins',
            'CircleCI',
            'TravisCI',
            'GitLab CI',
            'Codeship',
            'Codefresh',
            'Rust',
            'Go',
            'Swift',
            'Kotlin',
            'Scala',
            'Erlang',
            'Clojure',
            'Haskell',
            'F#',
            'Dart',
            'TypeScript',
            'Bash',
            'PowerShell',
            'C',
            'Objective-C',
            'Assembly',
            'R',
            'MATLAB',
            'Perl',
            'Lua',
            'Delphi',
            'Visual Basic',
            'VBA',
            'Fortran',
            'Ada',
            'Lisp',
            'Prolog',
            'Scheme',
            'Forth',
            'COBOL',
            'RPG',
            'CI/CD',
            'DevOps',
            'TDD',
            'DDD',
            'SOLID',
            'Design Patterns',
            'Microservices',
            'Serverless',
            'REST',
            'GraphQL',
            'SOAP',
            'gRPC',
            'WebSockets',
            'OAuth',
            'OpenAPI',
            'React JS',
            'React Native',
            'Redux',
            'Vue.js',
            'Vuex',
            'Angular',
            'RxJS',
            'Ember.js',
            'Backbone.js',
            'Polymer',
            'jQuery',
            'D3.js',
            'Bootstrap',
            'Material UI',
            'Tailwind CSS',
            'Sass',
            'Less',
            'Stylus',
            'Bulma',
            'Foundation',
            'Jest',
            'Mocha',
            'Cypress',
            'Enzyme',
            'Laravel'
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
            'Apprentissage',
            'Professionnalisation',
            'Autre'
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
            $user->setPassword(password_hash('Bonjour', PASSWORD_DEFAULT));
            $user->setStatus($userRandomRole);
            $user->setIsVerified($faker->boolean());
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

        
        for($i= 0 ; $i <=500 ; $i++){
            $offer = new Offer();
            $offer->setTitle($faker->jobTitle());
            $offer->setShortDescription($faker->paragraph);
            $offer->setContent($faker->paragraph(mt_rand(3, 6)));
            $offer->setSalary(mt_rand(30000, 100000)*100);
            $offer->setLocation($faker->city());
            $offer->setContractType($faker->randomElement($contractTypes));
            $offer->setEntreprise($faker->randomElement($recruteurs));
            $offer->setSlug($faker->slug());
            $randomTags = $faker->randomElements($tags, mt_rand(3, 8));

            foreach ($randomTags as $tag) {
                $offer->addTag($tag);
            }
            $manager->persist($offer);
        }
        $manager->flush();

        // Insertion des candidatures

        $candidates = $manager->getRepository(User::class)->findByStatus('Candidat');
        $offers = $manager->getRepository(Offer::class)->findAll();

        for($i=0; $i <200 ; $i++){
            $randomCandidate = $faker->randomElement($candidates);
            $randomOffer = $faker->randomElement($offers);
            $application = new Application();
            $application->setUser($randomCandidate);
            $application->setOffer($randomOffer);
            $application->setEntreprise($randomOffer->getEntreprise());
            $application->setMessage($faker->paragraph(mt_rand(1, 3)));
            $application->setCreatedAt(new DateTimeImmutable());
            $application->setStatus($faker->randomElement(['STATUS_PENDING', 'STATUS_ACCEPTED', 'STATUS_REFUSED']));
            $manager->persist($application);
        }

        // Création d'un compte admin

        $user = new User();
        $user->setEmail('romy@romy.com');
        $user->setPassword(password_hash('romy@romy.com', PASSWORD_DEFAULT));
        $user->setStatus('Admin');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setUsername('Romy Admin');
        $user->setIsVerified(true);
        $manager->persist($user);
        $manager->flush();
    }
}


