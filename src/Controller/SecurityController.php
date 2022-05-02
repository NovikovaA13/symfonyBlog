<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig_Environment;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @var Twig_Environment
     */
    private $twig;

    public function __construct
    (Twig_Environment $twig)
    {
        $this->twig = $twig;
    }
    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        $lastUser = $authenticationUtils->getLastUsername();

        $error = $authenticationUtils->getLastAuthenticationError();
        return new Response($this->twig->render('security/login.html.twig', [
            'last_user' => $lastUser,
            'error' => $error
        ]));
    }
    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
    }
}
