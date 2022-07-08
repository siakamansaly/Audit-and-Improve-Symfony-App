<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Controller to manage authentication and security.
 *
 * @author  Siaka MANSALY <siaka.mansaly@gmail.com>
 *
 * @package: App\Controller
 */
class SecurityController extends AbstractController
{
    /**
     * Login page.
     *
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('homepage');
        }

        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * Logout page.
     *
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
    }
}
