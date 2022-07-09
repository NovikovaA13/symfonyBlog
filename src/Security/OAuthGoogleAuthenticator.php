<?php
declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\GoogleUser;
use Symfony\Bundle\FrameworkBundle\Tests\CacheWarmer\testRouterInterfaceWithoutWarmebleInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Routing\RouterInterface;

use KnpU\OAuth2ClientBundle\Client\OAuth2Client;
use League\OAuth2\Client\Token\AccessToken;
use Symfony\Component\Security\Core\User\UserInterface;

class OAuthGoogleAuthenticator extends SocialAuthenticator
{
    /**
     * @var ClientRegistry
     */
    private $clientRegistry;
    /**
     * @var EntityManagerInterface;
     */

    private $em;
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @param ClientRegistry $clientRegistry
     * @param EntityManagerInterface $em
     * @param UserRepository $userRepository
     */
    public function __construct
    (
        ClientRegistry $clientRegistry,
        EntityManagerInterface $em,
        UserRepository $userRepository,
        RouterInterface $router

    )
    {
        $this->clientRegistry = $clientRegistry;
        $this->em = $em;
        $this->userRepository = $userRepository;
        $this->router = $router;
    }

    /**
     * @param Request $request
     * @param AuthenticationException|null $authException
     * @return RedirectResponse
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse(
            'connect',
            Response::HTTP_TEMPORARY_REDIRECT
        );
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function supports(Request $request):bool
    {
        return $request->attributes->get('_route') === 'google_auth';
    }

    public function getCredentials(Request $request)
    {
       return $this->fetchAccessToken($this->getGoogleClient());
    }

    /**
     * @param mixed $credentials
     * @param UserProviderInterface $userProvider
     * @return \App\Entity\User|\Symfony\Component\Security\Core\User\UserInterface|void|null
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        /** @var GoogleUser $googleUser */
        $googleUser = $this->getGoogleClient()
            ->fetchUserFromToken($credentials);
        $email = $googleUser->getEmail();
        $clientId = $googleUser->getId();
        $existingUser = $this->userRepository->findOneBy(['clientId' => $clientId]);
        if($existingUser){
            return $existingUser;
        }
        $user = $this->userRepository->findOneBy(['email' => $email]);
        if(!$user){
            $user = User::fromGoogleRequest(
                $googleUser->getId(),
                $email,
                $googleUser->getName()
            );
            $this->em->persist($user);
            $this->em->flush();
        }

    }

    /**
     * @param Request $request
     * @param AuthenticationException $authenticationException
     * @return Reponse|null
     */
    public function onAuthenticationFailure
    (
        Request $request,
        AuthenticationException $authenticationException
    ): ?Reponse
    {
        return new Response('Authentication failed', 403);
    }

    /**
     * @param Request $request
     * @param TokenInterface $token
     * @param string $providerKey
     * @return Response|null
     */
    public function onAuthenticationSuccess
    (
        Request $request,
        TokenInterface $token,
        $providerKey
    ): ?Response
    {

        return new RedirectResponse($this->router->generate('blog_posts'));

    }

    /**
     * @return \KnpU\OAuth2ClientBundle\Client\OAuth2ClientInterface
     */
    public function getGoogleClient()
    {
        return $this->clientRegistry->getClient('google');
    }

    /**
     * @return bool
     */
    public function supportsRememberMe(): bool
    {
        return true;
    }
}