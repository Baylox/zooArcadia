<?php

namespace App\Form;

use App\Entity\Animal;
use App\Entity\Rapport;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RapportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Champs de l'entité Rapport
            ->add('titre')
            ->add('dateRapport', null, [
                'widget' => 'single_text',
            ])
            ->add('details')
            ->add('animal', EntityType::class, [
                'class' => Animal::class,
                'choice_label' => 'prenom',
                'required' => false,
            ])
            ->add('utilisateur', EntityType::class, [
                'class' => Utilisateur::class,
                'choice_label' => 'prenom',
            ])

            // Champs de l'entité Alimentation directement inclus
            ->add('nomNourriture', null, [
                'mapped' => false,
                'label' => 'Nom de la Nourriture',
            ])
            ->add('quantiteNourriture', NumberType::class, [
                'mapped' => false,
                'label' => 'Quantité de Nourriture',
                'scale' => 2,
            ])
            ->add('commentaireVeterinaire', null, [
                'mapped' => false,
                'label' => 'Commentaire du Vétérinaire',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rapport::class,
        ]);
    }
}

