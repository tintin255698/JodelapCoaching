<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('FirstName', TextType::class, ['label' => 'Prénom', 'attr'=>['placeholder'=>'Votre prénom'] ])
            ->add('LastName', TextType::class, ['label' => 'Nom', 'attr'=>['placeholder'=>'Votre Nom'] ])
            ->add('Email', EmailType::class, ['label' => 'Email',  'attr'=>['placeholder'=>'Votre email'] ] )
            ->add('telephone', TextType::class, ['label' => 'Téléphone',  'attr'=>['placeholder'=>'Votre téléphone'] ])
            ->add('plainPassword', RepeatedType::class, array(
                'first_name' => 'pass',
                'second_name' => 'confirm',
                'type' => PasswordType::class,
                'label'=>'Mot de passe',
                'attr'=>['placeholder'=>'Entrer votre mot de passe'],
                'mapped' => false,
                'constraints' => array (
                    new NotBlank([
                        'message' => "Merci d'entrer un mot de passe",
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au minimum 6 caracteres',
                        'max' => 4096,
                    ])),
                'first_options' => array('label' => 'Mot de passe', 'attr'=>['placeholder'=>'Entrer votre mot de passe']),
                'second_options'=> array('label' => 'Confirmation de votre mot de passe', 'attr'=>['placeholder'=>'Confirmez votre mot de passe']),
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
