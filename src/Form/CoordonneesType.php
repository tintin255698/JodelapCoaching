<?php

namespace App\Form;

use App\Entity\User;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CoordonneesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('FirstName', \Symfony\Component\Form\Extension\Core\Type\TextType::class, ['label' => 'Prénom', 'attr'=>['placeholder'=>'Votre email'] ])
            ->add('LastName', \Symfony\Component\Form\Extension\Core\Type\TextType::class, ['label' => 'Nom', 'attr'=>['placeholder'=>'Votre email'] ])
            ->add('Email', EmailType::class, ['label' => 'Email', 'attr'=>['placeholder'=>'Votre email'] ] )
            ->add('telephone', TelType::class, ['label' => 'Téléphone', 'attr'=>['placeholder'=>'Votre numéro de téléphone'] ] )
            ->add('information', TextareaType::class, ['label' => 'Informations supplémentaires avant de confirmer',  'mapped' =>false, 'required' => false, 'attr'=>['placeholder'=>'(Facultatif)'
               ] ] )
            ->add('Valider', SubmitType::class,  ['label' => 'Valider', 'attr' => [
                'class' => 'btn btn-dark mt-4' ]] )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
