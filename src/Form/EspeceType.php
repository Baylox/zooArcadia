<?php

namespace App\Form;

use App\Entity\Espece;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EspeceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('typeEspece', TextType::class, [
                'label' => 'Type d\'espèce',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('evaluationExtinction', TextType::class, [
                'label' => 'Évaluation de l\'extinction',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('traitsCaracteristiques', TextareaType::class, [
                'label' => 'Traits caractéristiques',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Espece::class,
        ]);
    }
}

