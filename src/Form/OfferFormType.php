<?php

namespace App\Form;

use App\Entity\Tag;
use App\Entity\Offer;
use App\Entity\ContractType;
use App\Entity\EntrepriseProfil;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class OfferFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class,[
                'label'=>false,
                'attr' =>[
                    'placeholder'=>'Titre de l\'offre d\'emploi'
                ]
            ])
            ->add('shortDescription',TextareaType::class,[
                'label'=>false,
                'attr' =>[
                    'placeholder'=>'Description courte de l\'offre d\'emploi',
                    'rows'=>3
                ]
            ])
            ->add('content',TextareaType::class,[
                'label'=>false,
                'attr' =>[
                    'placeholder'=>'Description de l\'offre d\'emploi',
                    'rows'=>6
                ]
            ])
            ->add('salary',MoneyType::class,[
                'label'=>false,
                'attr' =>[
                    'placeholder'=>'Salaire proposé'
                ]
            ])
            ->add('location',TextType::class,[
                'label'=>false,
                'attr' =>[
                    'placeholder'=>'Lieu de travail'
                ]
            ])
            ->add('contractType', EntityType::class, [
                'label'=>false,
                'class' => ContractType::class,
                'choice_label' => 'name',
            ])
            ->add('tags', EntityType::class, [
                'label'=>'Compétences requises',
                'class' => Tag::class,
                'choice_label' => 'name',
                'multiple' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offer::class,
        ]);
    }
}
