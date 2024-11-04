<?php

namespace App\Form;

use App\Entity\Animal;
use App\Entity\Rapport;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class RapportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('titre')
        ->add('dateRapport', null, [
            'widget' => 'single_text',
        ])
        ->add('animal', EntityType::class, [
            'class' => Animal::class,
            'choice_label' => 'prenom',
            'placeholder' => 'Sélectionnez un animal',
        ]) 
        ->add('alimentations', CollectionType::class, [
            'entry_type' => AlimentationType::class,
            'entry_options' => ['label' => false],
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
        ])       
        ->add('details')
        ->add('utilisateur', EntityType::class, [
            'class' => Utilisateur::class,
            'choice_label' => 'prenom',
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rapport::class,
        ]);
    }
}
