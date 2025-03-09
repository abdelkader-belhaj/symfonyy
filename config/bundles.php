<?php

return [
    // ✅ Bundle interne (officiel Symfony) - Fournit le cœur du framework Symfony
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],

    // ✅ Bundle interne (officiel Symfony) - Intègre Doctrine ORM pour gérer les bases de données
    Doctrine\Bundle\DoctrineBundle\DoctrineBundle::class => ['all' => true],

    // ✅ Bundle interne (officiel Symfony) - Gère les migrations de base de données
    Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle::class => ['all' => true],

    // ✅ Bundle interne (officiel Symfony) - Ajoute des outils de débogage (uniquement en mode dev)
    Symfony\Bundle\DebugBundle\DebugBundle::class => ['dev' => true],
    
    // ✅ Bundle interne (officiel Symfony) - Active Twig, le moteur de templates
    Symfony\Bundle\TwigBundle\TwigBundle::class => ['all' => true],

    // ✅ Bundle interne (officiel Symfony) - Fournit la barre d'outils de débogage et le profiler (dev & test)
    Symfony\Bundle\WebProfilerBundle\WebProfilerBundle::class => ['dev' => true, 'test' => true],

    // ✅ Bundle externe (Symfony UX) - Intègre Stimulus.js pour améliorer l’interactivité avec JavaScript
    Symfony\UX\StimulusBundle\StimulusBundle::class => ['all' => true],

    // ✅ Bundle externe (Symfony UX) - Intègre Turbo.js pour améliorer les performances en évitant les rechargements de page
    Symfony\UX\Turbo\TurboBundle::class => ['all' => true],

    // ✅ Bundle interne (officiel Symfony) - Gère la sécurité et l’authentification des utilisateurs
    Symfony\Bundle\SecurityBundle\SecurityBundle::class => ['all' => true],

    // ✅ Bundle interne (officiel Symfony) - Fournit des commandes pour générer des fichiers (entités, contrôleurs, etc.) en mode dev
    Symfony\Bundle\MakerBundle\MakerBundle::class => ['dev' => true],

    // ✅ Bundle externe (Symfony UX) - Intègre Chart.js pour afficher des graphiques dans les templates Twig
    Symfony\UX\Chartjs\ChartjsBundle::class => ['all' => true],

    // ✅ Bundle externe (tiers) - Ajoute la pagination pour paginer les résultats des requêtes Doctrine
    Knp\Bundle\PaginatorBundle\KnpPaginatorBundle::class => ['all' => true],

    // ✅ Bundle interne (officiel Symfony) - Fournit Monolog pour gérer les logs et les erreurs de l'application
    Symfony\Bundle\MonologBundle\MonologBundle::class => ['all' => true],

    // ✅ Bundle externe (Twig Extra) - Ajoute des extensions supplémentaires pour Twig (ex : filtres, fonctions avancées)
    Twig\Extra\TwigExtraBundle\TwigExtraBundle::class => ['all' => true],
];
