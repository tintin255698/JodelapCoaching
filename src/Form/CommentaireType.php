<?php

namespace App\Form;

use App\Entity\Commentaire;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('note',IntegerType::class, ['label' => 'Note entre 0 et 5', 'attr'=>['placeholder'=>'Votre note entre 0 (Mauvais) à 5 (Excellent)'] ])
            ->add('contenu', TextType::class, ['label' => 'Avis', 'attr'=>['placeholder'=>'Votre avis' ] ])
            ->add('valider', SubmitType::class, ['label' => 'Ajouter votre avis', 'attr' => [
                'class' => 'btn btn-dark' ]] )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commentaire::class,
        ]);
    }
}
