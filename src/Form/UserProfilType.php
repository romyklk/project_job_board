<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\UserProfil;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class UserProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entre votre prénom'
                ]
            ])
            ->add('lastName', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entre votre nom'
                ]
            ])
            ->add('address', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entre votre adresse'
                ]
            ])
            ->add('city', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entre votre ville'
                ]
            ])
            ->add('zipCode', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entre votre code postal'
                ]
            ])
            ->add('country', CountryType::class, [
                'label' => false,
                'preferred_choices' => [
                    'FR',
                    'BE',
                    'CH',
                    'LU',
                ],

            ])
            ->add('phoneNumber', TelType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entre votre numéro de téléphone'
                ]
            ])
            ->add('jobSought', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entre le poste recherché'
                ]
            ])
            ->add('presentation', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Présentez-vous en quelques mots',
                    'rows' => '5'
                ]
            ])
            ->add('availability', CheckboxType::class, [
                'label' => 'Etes-vous disponible ?',
            ])
            ->add('website', UrlType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entre votre site web'
                ]
            ])
            ->add('imageFile', FileType::class, [
                'label' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '3M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                            'image/jpg'
                        ],
                        'mimeTypesMessage' => 'Merci d\'uploader une image  de type jpg, jpeg, png ou webp',
                    ])
                ],

            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserProfil::class,
        ]);
    }
}
