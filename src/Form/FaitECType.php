<?php

namespace App\Form;

use App\Entity\FaitEC;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FaitECType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('annee')
            ->add('indicateur', EntityType::class, array('class' => 'App\Entity\Indicateur','placeholder'=>'Indicateur'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FaitEC::class,
        ]);
    }
}
