<?php

namespace App\Form;

use App\Entity\Commune;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommuneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codeCommune')
            ->add('nomCommune')
            ->add('departement', EntityType::class, array('class' => 'App\Entity\Departement','placeholder'=>'Veuillez selectionner le departement'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Commune::class,
        ]);
    }
}
