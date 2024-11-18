<?php

namespace App\Form;

use App\Entity\Alimentation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class AlimentationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nomNourriture', TextType::class, [
            'label' => 'Nom de la Nourriture',
            'attr' => ['class' => 'form-control'],
        ])
        ->add('commentaireVeterinaire', TextareaType::class, [
            'label' => 'Commentaire du Vétérinaire',
            'attr' => ['class' => 'form-control', 'rows' => 3],
            'required' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Alimentation::class,
        ]);
    }
}
