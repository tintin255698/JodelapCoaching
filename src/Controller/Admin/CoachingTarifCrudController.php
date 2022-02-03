<?php

namespace App\Controller\Admin;

use App\Entity\CoachingTarif;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CoachingTarifCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CoachingTarif::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IntegerField::new('heure', "Nombre d'heures"),
            MoneyField::new('priceForTwo', 'Prix pour deux')->setCurrency('EUR'),
            MoneyField::new('priceForThree', 'Supplément prix pour 3')->setCurrency('EUR'),
            MoneyField::new('priceUnity', "Prix à l'unité")->setCurrency('EUR'),
            TextField::new('titre', 'Titre')
        ];
    }

}
