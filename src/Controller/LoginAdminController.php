<?php
namespace App\Controller;

use App\Entity\Admin;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\User\LoginFormType;


class LoginAdminController extends AbstractController
{
    #[Route('/admin/login', name: 'app_admin_login', methods: ['GET', 'POST'])]
    public function login(Request $request, EntityManagerInterface $entityManager): Response
    {
        $error = null;
        $predefinedEmail = 'admin@example.com';
        $predefinedPassword = 'admin123';

        $email = $predefinedEmail;
        $password = $predefinedPassword;

        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $password = $request->request->get('password');

            $admin = $entityManager->getRepository(Admin::class)->findOneBy(['email' => $email]);

            if (!$admin) {
                $error = "Administrateur introuvable.";
            } elseif ($admin->getPassword() !== $password) {
                $error = "Mot de passe incorrect.";
            } else {
                return $this->redirectToRoute('app_base_admin');
            }
        }

        return $this->render('admin/LoginAdmin.html.twig', [
            'error' => $error,
            'predefinedEmail' => $predefinedEmail,
            'predefinedPassword' => $predefinedPassword
        ]);
    }

    #[Route('/admin/dashboard', name: 'app_admin_dashboard')]
    public function adminDashboard(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    #[Route('/admin/base', name: 'app_base_admin')]
    public function baseAdmin(): Response
    {
        return $this->render('admin/baseAdmin.html.twig');
    }
}
