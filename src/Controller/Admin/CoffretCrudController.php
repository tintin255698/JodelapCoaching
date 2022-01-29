<?php

namespace App\Controller\Admin;

use App\Entity\Coffret;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CoffretCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Coffret::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            MoneyField::new('prix')->setCurrency('EUR'),
            ImageField::new('image')->setBasePath('public/uploads/coffret')
                ->setUploadDir('public/uploads/coffret')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false),
            TextField::new('produit', 'Descriptif du produit'),
        ];
    }

}
