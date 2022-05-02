<?php
declare(strict_types=1);

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Comment;

/**
 * @ORM\Entity()
 */
class User implements UserInterface
{
    public const GITHUB_OAUTH = 'Github';
    public const GOOGLE_OAUTH = 'Google';

    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_ADMIN = 'ROLE_ADMIN';
    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="bigint")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $clientId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $oauthType;

    /**
     * @var DateTimeInterface
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastLogin;

    /**
     * @var array
     *
     * @ORM\Column(type="json_array")
     */
    private $roles = [];
    /**
     * @var Comment[]
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="user")
     */
    private $comments;
    /**
     * @param $clientId
     * @param string $email
     * @param string $username
     * @param string $oauthType
     * @param array $roles
     */
    public function __construct(
        $clientId,
        string $email,
        string $username,
        string $oauthType,
        array $roles
    ){
        $this->clientId = $clientId;
        $this->email = $email;
        $this->username = $username;
        $this->oauthType = $oauthType;
        $this->roles = $roles;
        $comments = new ArrayCollection();
    }

    /**
     * @param int $clientId
     * @param string $email
     * @param string $username
     * @return User
     */
    public static function fromGithubRequest
    (
        string $clientId,
        string $email,
        string $username
    ):User
    {
    return new self(
        $clientId,
        $email,
        $username,
        self::GITHUB_OAUTH,
        [self::ROLE_USER]
    );
    }    public static function fromGoogleRequest
    (
        string $clientId,
        string $email,
        string $username
    ):User
    {
    return new self(
        $clientId,
        $email,
        $username,
        self::GOOGLE_OAUTH,
        [self::ROLE_USER]
    );
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getClientId()
    {
         return $this->clientId;
    }
    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getOauthType(): string
    {
        return $this->oauthType;
    }

    /**
     * @return DateTimeInterface
     */
    public function getLastLogin(): DateTimeInterface
    {
        return $this->lastLogin;
    }
    /**
     * @return null
     */
    public function getPassword(): void
    {
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return [
            'ROLE_USER'
        ];
    }


    /**
     * @return null|string
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->email;
    }

    public function eraseCredentials():void
    {
    }
    /**
     * @return bool
     */
    public function getEnable(): bool
    {
        return $this->enable;
    }

    /**
     * @param bool $enable
     * @return $this
     */
    public function setEnable(bool $enable): self
    {
        $this->enable = $enable;

        return $this;
    }
}
