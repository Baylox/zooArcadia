<?php


namespace App\Form;

use App\Entity\Animal;
use App\Entity\Rapport;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class RapportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('dateRapport', null, [
                'widget' => 'single_text',
            ])
            ->add('details')
            ->add('nomNourriture', TextType::class, [
                'label' => 'Nom de la Nourriture',
            ])
            ->add('quantiteNourriture', NumberType::class, [
                'label' => 'Quantité de Nourriture',
                'scale' => 2,
            ])
            ->add('changementAlimentation', CheckboxType::class, [
                'label' => 'Changement alimentaire',
                'required' => false,
            ])
            ->add('animal', EntityType::class, [
                'class' => Animal::class,
                'choice_label' => 'prenom',
                'placeholder' => 'Sélectionnez un animal',
            ])
            ->add('utilisateur', EntityType::class, [
                'class' => Utilisateur::class,
                'choice_label' => 'prenom',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rapport::class,
        ]);
    }
}
