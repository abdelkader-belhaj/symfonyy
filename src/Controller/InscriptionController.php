<?php
namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\User\LoginFormType;


class InscriptionController extends AbstractController
{
    #[Route('/inscription', name: 'app_inscription', methods: ['GET', 'POST'])]
    public function inscription(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $nom = $request->request->get('nom');
            $prenom = $request->request->get('prenom');
            $email = $request->request->get('email');
            $password = $request->request->get('password');
            $role = $request->request->get('role'); // Récupérer le rôle choisi

            // Vérifier si l'utilisateur existe déjà
            $existingUser = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

            if ($existingUser) {
                return new Response('Erreur : cet email est déjà utilisé.', 400);
            }

            // Créer un nouvel utilisateur
            $user = new User();
            $user->setNom($nom);
            $user->setPrenom($prenom);
            $user->setEmail($email);
            $user->setPassword($password); // ⚠️ Mot de passe non sécurisé pour l'instant
            $user->setRoles([$role]); // Définir le rôle choisi

            // Sauvegarde en base de données
            $entityManager->persist($user);
            $entityManager->flush();

            // Rediriger vers la page de connexion après l'inscription
            return $this->redirectToRoute('app_login');
        }

        // Afficher le formulaire si la requête est en GET
        return $this->render('frontoffice/inscription.html.twig');
    }
}

