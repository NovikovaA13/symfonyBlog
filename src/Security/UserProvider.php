<?php
declare(strict_types=1);

namespace App\Security;
use App\Entity\User;
use App\Repository\UserRepository;
use MongoDB\Driver\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;


class UserProvider implements UserProviderInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param $username
     * @return mixed
     */
    public function loadUserByUsername($username)
    {
        return $this->userRepository->loadUserByUsername($username);
    }

    /**
     * @param UserInterface $user
     * @return UserInterface
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        if(!$user instanceof User){
            throw new UnsupportedUserException(
                sprintf('Instance of "%s" are not supported', get_class($user))
            );
        }
        return $user;
    }

    /**
     * @param string $class
     * @return bool
     */
    public function supportsClass($class): bool
    {
        return $class === 'App\Entity\User';
    }
}