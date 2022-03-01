<?php

namespace App\Controller\Admin;

use App\Entity\Image;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichFileType;



class ImageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Image::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('image', 'Image (même proportion)'),
            TextareaField::new('imageFile', 'Image ou vidéo à télécharger')->setFormType(VichFileType::class),
            IntegerField::new('ordre', 'donner un numéro(fonctionne de manière croissante'),
            ChoiceField::new('type', 'Type : video/image')
                ->setChoices([  'video' => 'video',
                        'image' => 'image',
                        ]
                ),
            ];
    }
}
