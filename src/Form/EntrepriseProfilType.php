<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\EntrepriseProfil;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class EntrepriseProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entrez le nom de votre entreprise'
                ]
            ])
            ->add('address',TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entrez l\'adresse de votre entreprise'
                ]
            ])
            ->add('city',TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entrez la ville de votre entreprise'
                ]
            ])
            ->add('zipCode',TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entrez le code postal de votre entreprise'
                ]
            ])
            ->add('country',CountryType::class,[
                'preferred_choices' => ['FR', 'BE', 'CH', 'LU'],
                'label' => false,
            ])
            ->add('phoneNumber',TelType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entrez le numéro de téléphone de votre entreprise'
                ]
            ])
            ->add('activityArea',TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entrez le secteur d\'activité de votre entreprise'
                ]
            ])
            ->add('email',EmailType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entrez l\'adresse email de votre entreprise'
                ]
            ])
            ->add('description',TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entrez la description de votre entreprise',
                    'rows' => '7'
                ]
            ])
            ->add('logo',FileType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entrez le logo de votre entreprise'
                ]
            ])
            ->add('website',UrlType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entrez le site web de votre entreprise'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EntrepriseProfil::class,
        ]);
    }
}
