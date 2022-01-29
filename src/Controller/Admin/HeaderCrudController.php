<?php

namespace App\Controller\Admin;

use App\Entity\Header;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class HeaderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Header::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            ImageField::new('image')->setBasePath('public/uploads/evenement')
                ->setUploadDir('public/uploads/header')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false),
            TextField::new('titre', "Titre page d'accueil"),
            TextareaField::new('texte', 'Texte descriptif'),
            TextField::new('btn1', "Texte bouton gauche"),
            TextField::new('btn2', "Texte bouton droite"),
            UrlField::new('urlBtn1', 'Adresse web du bouton gauche'),
            UrlField::new('urlBtn2', 'Adresse web du bouton droite'),
        ];
    }

}
