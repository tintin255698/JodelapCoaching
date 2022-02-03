<?php

namespace App\Controller\Admin;

use App\Entity\ResaCoaching;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use phpDocumentor\Reflection\Types\Integer;

class ResaCoachingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ResaCoaching::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('numeroDeCommande', 'Numéro de commande'),
            DateTimeField::new('dateResa', 'Date de réservation'),
            AssociationField::new('coaching', 'Session coaching'),
            AssociationField::new('evenement', 'Evènements réservés'),
            BooleanField::new('resaConfirm', 'Confirmation de réservation'),
            AssociationField::new('user', 'Utilisateur'),
            IntegerField::new('prix', 'Prix'),
            IntegerField::new('nbPersonne', 'Nombre de personne'),
        ];
    }
}
