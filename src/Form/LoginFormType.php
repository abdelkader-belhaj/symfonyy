<?php

namespace App\Form;

use App\Entity\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class LoginFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Adresse Email'
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe'
            ])
            // Si tu veux ajouter un champ de rôle dans le formulaire (par exemple, pour choisir le rôle), tu peux ajouter ceci :
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Étudiant' => 'Etudiant',
                    'Enseignant' => 'Enseignant',
                    'Psychologue'=> 'Psychologue'
                ],
                'multiple' => true, // Permet plusieurs rôles
                'expanded' => true, // Affiche les options sous forme de cases à cocher
                'label' => 'Sélectionner un rôle',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
