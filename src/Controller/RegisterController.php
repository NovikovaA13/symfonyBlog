<?php
declare(strict_types=1);

namespace App\Controller;

use App\Event\RegisteredUserEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Annotation\Response;
use App\Entity\User;
use App\Form\UserType;
use App\Service\CodeGenerator;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function register()
    {
        return $this->render('security/register.html.twig');
    }
    /**
     * @Route("/confirm/{code}", name="email_confirmation")
     */
    public function confirmEmail(UserRepository $userRepository, string $code)
    {
        $user = $userRepository->findOneBy(['confirmationCode' => $code]);
        if(!$user){
            return new Response('404');
        }
        $user->setEnable(true);
        $user->setConfirmationCode('');
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        return $this->render('security/account_confirm.html.twig', [
            'user' => $user
        ]);
    }
}
