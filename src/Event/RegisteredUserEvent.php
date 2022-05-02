<?php
declare(strict_types=1);

namespace App\Event;
use Symfony\Component\EventDispatcher\Event;
use App\Entity\User;

class RegisteredUserEvent extends Event
{
    public const NAME = 'user.register';
    /**
     * @var User
     */
    private $registerUser;

    /**
     * @param User $registerUser
     */
    public function __construct(User $registerUser)
    {
        $this->registerUser = $registerUser;
    }

    /**
     * @return User
     */
    public function getRegisterUser()
    {
        return $this->registerUser;
    }
}