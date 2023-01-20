<?php

namespace App\Form;

use App\Entity\TypeCE;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RapportTableauType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           ->add('indicateur', EntityType::class, array('class' => 'App\Entity\Indicateur','placeholder'=>'Veuillez selectionner l\'indicateur'))
        ;
    }
}
