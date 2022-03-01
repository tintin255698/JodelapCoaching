<?php
namespace App\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class contactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',EmailType::class, ['label' => 'Email :', 'attr'=>['placeholder'=>'Votre email'] ])
            ->add('sujet',ChoiceType::class, ['label' => 'Sujet :',
                'choices' => [
                    'Évènements' => 'Évènements',
                    'Coachings' => 'Coachings',
                    'Coffrets cadeaux' => 'Coffrets cadeaux',
                    'Autre'=> 'Autre'
                ],
            ])
            ->add('message', TextareaType::class, ['label' => 'Message :', 'attr'=>['placeholder'=>'Votre Message']])
            ->add('Envoyer', SubmitType::class, ['label' => 'Envoyer',
                'attr' => [
                    'class' => 'btn btn-dark mt-3'
                ]
            ])
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
        ]);
    }
}