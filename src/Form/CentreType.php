<?php

namespace App\Form;

use App\Entity\Centre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CentreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomCE')
            ->add('adresseCE')
            ->add('chefCE')
            ->add('numeroChefCE')
            ->add('dateCreationCE')
            ->add('commune', EntityType::class, array('class' => 'App\Entity\Commune','placeholder'=>'Commune'))
            ->add('typeCE', EntityType::class, array('class' => 'App\Entity\TypeCE','placeholder'=>'Type du centre'))
            ->add('zoneImCE', EntityType::class, array('class' => 'App\Entity\ZoneImpCE','placeholder'=>'Zone d implementation'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Centre::class,
        ]);
    }
}
