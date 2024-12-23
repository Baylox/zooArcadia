<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Employé' => 'ROLE_EMPLOYE',
                    'Vétérinaire' => 'ROLE_VETERINAIRE',
                ],
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('nom')
            ->add('prenom')

             // Le champ mot de passe est optionnel lors de l'édition
            ->add('password', PasswordType::class, [
                'required' => false,  // Le mot de passe n'est pas obligatoire
                'mapped' => false,    // On gère manuellement le mot de passe
                'help' => 'Laissez vide pour conserver le mot de passe actuel',  // Petit message d'aide pour l'admin
            ]);
        }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
            'is_edit' => false,  
        ]);
    }
}
