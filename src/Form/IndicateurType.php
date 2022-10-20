<?php

namespace App\Form;

use App\Entity\Indicateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IndicateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomIndicateur')
            ->add('descriptionIndicateur')
            ->add('indicateurBase', EntityType::class, array('class' => 'App\Entity\IndicateurBase','placeholder'=>'Veuillez selectionner l indicateur de base'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Indicateur::class,
        ]);
    }
}
