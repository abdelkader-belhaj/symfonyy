<?php
// src/Form/ActivityType.php

namespace App\Form;

use App\Entity\Activity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\All;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActivityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('titre', null, [
            'constraints' => [
                new NotBlank(['message' => 'Le titre ne peut pas être vide.']),
                new Length(['min' => 3, 'max' => 255, 'minMessage' => 'Le titre doit contenir au moins {{ limit }} caractères.']),
            ],
        ])
        ->add('description', null, [
            'constraints' => [
                new NotBlank(['message' => 'La description ne peut pas être vide.']),
                new Length([
                    'min' => 10,
                    'max' => 1000,
                    'minMessage' => 'La description doit comporter au moins {{ limit }} caractères.',
                    'maxMessage' => 'La description ne peut pas dépasser {{ limit }} caractères.',
                ]),
            ],
        ])            ->add('date', null, [
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank(['message' => 'La date ne peut pas être vide.']),
                    new GreaterThanOrEqual(['value' => 'today', 'message' => 'La date ne peut pas être dans le passé.']),
                ],
            ])
            ->add('imageFileName', FileType::class, [
                'label' => 'Images (JPG, PNG)',
                'required' => false,
                'mapped' => false,
                'multiple' => true,
                'constraints' => [
                    new All([
                        'constraints' => [
                            new Image([
                                'mimeTypes' => ['image/jpeg', 'image/png'],
                                'mimeTypesMessage' => 'Veuillez télécharger une image valide (JPG, PNG).',
                            ])
                        ]
                    ])
                ]
            ])
                    ->add('typesActivity', ChoiceType::class, [
                        'label'       => 'Type d\'activité',
                        'choices'     => [
                            'Sport'     => 'sport',
                            'Culture'   => 'culture',
                            'Éducation' => 'Education',
                            
                        ],
                        'placeholder' => 'Sélectionnez un ou plusieurs types',
                        'expanded'    => false,   // false pour une liste déroulante, true pour des cases à cocher ou boutons radio
                        'multiple'    => false,    // true pour permettre la sélection multiple
                        'constraints' => [
                            new NotBlank(['message' => 'Veuillez sélectionner au moins un type d\'activité.']),
                        ],
                    ]);
            

                    
            
            // Si vous n'utilisez plus la relation avec User, supprimez également ce champ
            // ->add('enseignant', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => 'id',
            // ])
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Activity::class,
        ]);
    }
}
