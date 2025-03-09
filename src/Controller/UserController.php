<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\User\LoginFormType;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

#[Route('/user')]
final class UserController extends AbstractController
{
    #[Route(name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/profile', name: 'app_user_profile', methods: ['GET'])]
    public function profile(UserRepository $userRepository, SessionInterface $session): Response
    {
        // Récupérer l'ID de l'utilisateur depuis la session
        $userId = $session->get('user_id'); // Assurez-vous que 'user_id' est stocké dans la session lors de la connexion
    
        // Vérifier si l'ID existe dans la session
        if (!$userId) {
            return $this->redirectToRoute('app_login'); // Rediriger vers la page de connexion si l'ID est introuvable
        }
    
        // Récupérer l'utilisateur depuis la base de données
        $user = $userRepository->find($userId);
    
        // Vérifier si l'utilisateur existe
        if (!$user) {
            return $this->redirectToRoute('app_login'); // Rediriger vers la page de connexion si l'utilisateur n'existe pas
        }
    
        // Afficher le profil de l'utilisateur
        return $this->render('user/profile.html.twig', [
            'user' => $user,
        ]);
    }
}
