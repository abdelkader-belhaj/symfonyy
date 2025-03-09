<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Étudiant' => 'Etudiant',
                    'Enseignant' => 'Enseignant',
                    'Psychologue'=> 'Psychologue'
                ],
                'multiple' => true, // Permet de choisir plusieurs rôles
                'expanded' => true, // Affiche les options sous forme de cases à cocher
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Mettre à jour' // Le texte du bouton de soumission
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
