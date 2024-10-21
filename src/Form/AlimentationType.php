<?php

namespace App\Form;

use App\Entity\Alimentation;
use App\Entity\Rapport;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class AlimentationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomNourriture')
            ->add('quantiteNourriture', NumberType::class, [
                'label' => 'Quantité de Nourriture',
                'scale' => 2, // Précision pour les décimales
            ])
            ->add('commentaireVeterinaire')
            ->add('rapport', EntityType::class, [
                'class' => Rapport::class,
                'choice_label' => 'titre',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Alimentation::class,
        ]);
    }
}
