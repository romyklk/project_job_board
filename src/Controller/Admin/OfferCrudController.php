<?php

namespace App\Controller\Admin;

use App\Entity\Offer;
use Cocur\Slugify\Slugify;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;

class OfferCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Offer::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title', 'Titre'),
            TextEditorField::new('shortDescription', 'Description courte'),
            TextEditorField::new('content', 'Description'),
            MoneyField::new('salary', 'Salaire')->setCurrency('EUR'),
            TextField::new('location', 'Lieu'),
            AssociationField::new('contractType', 'Type de contrat'),
            AssociationField::new('tags', 'Mot clés'),
            AssociationField::new('entreprise', 'Entreprise'),
            SlugField::new('slug')->setTargetFieldName('title')->hideOnIndex(),
        ];
    }
    
}
