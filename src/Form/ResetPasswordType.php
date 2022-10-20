<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('password', PasswordType::class, array(
                'mapped' => false,
                'label'=>'Ancien mot de passe'

            ))
            ->add('SecondPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'attr' => array(
                    'control-class' => 'col-md-12', 'class' => 'form-control form-control-line',

                ),
                'invalid_message' => 'Les deux mots de passe doivent être identiques',
                'options' => array(
                    'attr' =>array(
                        'class'=> 'password-field'


                    )
                ),
                'first_options'  => ['label' => 'Nouveau mot de passe'],
                'second_options' => ['label' => 'Repeter mot de passe'],
                'required'=> true,
            ))

        ;
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
