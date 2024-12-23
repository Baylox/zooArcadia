<?php

namespace App\Form;

use App\Document\Horaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class HoraireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('heureOuverture', TextType::class, [
            'label' => 'Heure d\'ouverture (HH:MM)',
            'attr' => ['placeholder' => 'HH:MM'],
            'constraints' => [
                new Regex([
                    'pattern' => '/^(?:[01]\d|2[0-3]):[0-5]\d$/',
                    'message' => 'Veuillez entrer une heure au format Heure:Minute.',
                ]),
            ],
        ])
        ->add('heureFermeture', TextType::class, [
            'label' => 'Heure de fermeture (HH:MM)',
            'attr' => ['placeholder' => 'HH:MM'],
            'constraints' => [
                new Regex([
                    'pattern' => '/^(?:[01]\d|2[0-3]):[0-5]\d$/',
                    'message' => 'Veuillez entrer une heure au format Heure:Minute.',
                ]),
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Horaire::class,
        ]);
    }
}




