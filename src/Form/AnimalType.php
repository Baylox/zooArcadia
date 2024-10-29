<?php

namespace App\Form;

use App\Entity\Animal;
use App\Entity\Espece;
use App\Entity\Habitat;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\File;

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
            ->add('image', FileType::class, [
                'label' => 'Image (fichier PNG ou JPEG)',
                'mapped' => false, // Le fichier est traité manuellement
                'required' => false,
                'constraints' => [
                    new File([
                        //'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger un fichier image valide',
                    ]),
                ],
            ])
            ->add('removeImage', CheckboxType::class, [
                'label' => 'Supprimer cette image',
                'mapped' => false,
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Animal::class,
        ]);
    }
}
