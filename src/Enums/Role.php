<?php

namespace App\Enums;

enum Role: string
{
    case ETUDIANT = 'ETUDIANT';
    case ENSEIGNANT = 'ENSEIGNANT';
    case PSYCHOLOGUE = 'PSYCHOLOGUE';

    public static function getChoices(): array
    {
        return [
            self::ETUDIANT->value => 'Étudiant',
            self::ENSEIGNANT->value => 'Enseignant',
            self::PSYCHOLOGUE->value => 'Psychologue'
        ];
    }
}
