<?php

namespace App\Controller\Admin;

use App\Entity\Commentaire;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CommentaireCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Commentaire::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IntegerField::new('note', 'Note du coaching'),
            TextField::new('contenu', 'Description'),
            AssociationField::new('user', 'Utilisateur qui a mis la note'),
            BooleanField::new('bool', 'Mettre le commentaire sur le site 1 / Désactiver 0 (par défaut 0)')
        ];
    }

}
