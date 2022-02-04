<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MdpType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
          $builder
              ->add('ancienMDP', PasswordType::class, ['label'=>'Ancien mot de passe', 'attr'=>['placeholder'=>'Entrer votre ancien mot de passe']])
              ->add('nouveauMDP', PasswordType::class, ['label'=>'Nouveau mot de passe', 'attr'=>['placeholder'=>'Saisir votre nouveau mot de passe']])
              ->add('confirmerMDP', PasswordType::class, ['label'=>'Confirmation du nouveau mot de passe', 'attr'=>['placeholder'=>'Confimer votre nouveau mot de passe']])
              ->add('changer', SubmitType::class,  ['label' => 'Valider', 'attr' => [
                  'class' => 'btn btn-dark mt-3' ]] )
          ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
        ]);
    }
}
