<?php

namespace App\Controller\Admin;

use App\Entity\Evenement;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;

class EvenementCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Evenement::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('titre', 'Nom de la scéance'),
            TextEditorField::new('descriptif', 'Description'),
            TextField::new('descriptionAccueil', "Description très très courte pour l'accueil"),
            TextField::new('niveau', 'Niveau'),
            TextField::new('lieu', 'Rue'),
            TextField::new('ville', 'Ville'),
            TextField::new('lieuPrecis', 'Lieu précis (parking etc)'),
            DateTimeField::new('datetime', 'Date et heure du début de la session'),
            TimeField::new('finSession', 'Heure fin de la session'),
            DateField::new('finResa', 'Date fin de la réservation'),
            MoneyField::new('prix')->setCurrency('EUR'),
            ImageField::new('image')->setBasePath('public/uploads/evenement')
                ->setUploadDir('public/uploads/evenement')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false),
            TextField::new('materiel', 'Matériels de la scéance'),
            TextField::new('protectionObligatoire', 'Protection obligatoire'),
            TextField::new('protectionConseillees', 'Protection conseillées'),
            TextField::new('autres', 'Autres matériels'),


        ];
    }

}
