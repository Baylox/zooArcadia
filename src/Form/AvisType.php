<?php

namespace App\Form;

use App\Document\Avis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class AvisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', TextType::class, [
                'label' => 'Votre pseudo',
                'required' => true,
                'constraints' => [
                    new Assert\Length([
                        'min' => 3,
                        'max' => 20,
                        'minMessage' => 'Le pseudo doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le pseudo ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Assert\Regex([ // Vérifie que le pseudo ne contient que des lettres, chiffres, tirets ou underscores
                        'pattern' => '/^[a-zA-Z0-9-_]+$/',
                        'message' => 'Le pseudo ne peut contenir que des lettres, chiffres, tirets ou underscores.', 
                    ]),
                ],
            ])
            ->add('commentaire', TextareaType::class, [
                'label' => 'Votre commentaire',
                'required' => true,
                'constraints' => [
                    new Assert\Length([
                        'min' => 10,
                        'max' => 500,
                        'minMessage' => 'Le commentaire doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le commentaire ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^[^<>]*$/',
                        'message' => 'Le commentaire ne peut pas contenir les caractères < ou >.', // Vérifie que le commentaire ne contient pas de balises HTML pour
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Avis::class,
        ]);
    }
}


