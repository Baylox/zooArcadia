<?php

namespace App\Form;

use App\Entity\Animal;
use App\Entity\Espece;
use App\Entity\Habitat;
use App\Entity\Rapport;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnimalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prenom')
            ->add('etat')
            ->add('sexe')
            ->add('description')
            ->add('dob', null, [
                'widget' => 'single_text',
            ])
            ->add('espece', EntityType::class, [
                'class' => Espece::class,
                'choice_label' => 'typeEspece',
            ])
            ->add('habitat', EntityType::class, [
                'class' => Habitat::class,
                'choice_label' => 'nom',
            ])
            ->add('rapports', EntityType::class, [
                'class' => Rapport::class,
                'choice_label' => 'id', // Afficher les titres des rapports
                'multiple' => true,
                'expanded' => true, // Utiliser des cases à cocher pour sélectionner plusieurs rapports
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Animal::class,
        ]);
    }
}
