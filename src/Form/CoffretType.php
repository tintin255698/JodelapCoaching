<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CoffretType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('heure', IntegerType::class,['label' => "Nombre d'heures",'attr'=>['placeholder'=>"1 heure minimum pour commander/0 pour supprimer",
                'error_bubbling' => true,
                'min' => 0 ]])
            ->add('information', TextareaType::class,['label' => "Informations complémentaires", 'attr'=>['placeholder'=>"Facultatif / 250 caractères maximum", 'maxlength' => 250],  'required' => false
                ])
            ->add('valider', SubmitType::class,['attr'=>['class'=>'btn btn-dark'] ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
