<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Comment;
use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\Uuid;
/**
 * @ORM\Entity()
 * @ORM\Table(name="posts", indexes={
        @ORM\Index(columns={"user_id"}),
        @ORM\Index(columns={"status"}),
        @ORM\Index(columns={"created_at"})
 *     })
 */
class Post
{
    public const DRAFT = 'draft';
    public const PUBLISHED = 'published';
    /**
     * @var Uuid
     * @ORM\Id()
     * @ORM\Column(type="uuid")
     */
    private $id;
	/**
     * @ORM\Column(type="string", nullable=true)
     */
    private $title;
	/**
     * @ORM\Column(type="text", nullable=true)
     */
    private $body;
	/**
     * @ORM\Column(type="string", nullable=true)
     */
    private $slug;
	/**
     * @ORM\Column(type="datetime")
     */
    private $created_at;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @var Comment[]
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="post")
     */
    private $comments;
    /**
     * @var string
     * @ORM\Column(name="status", nullable=true)
     */
    private $status;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="SET NULL", nullable=true)
     */
    private $user;


    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->created_at = new \DateTimeImmutable('now', new \DateTimeZone('Europe/Paris'));
        $this->comments = new ArrayCollection();
    }

    /**
     * @param string|null $title
     * @param string|null $slug
     * @param string|null $body
     * @return Post
     */
    public static function fromDraft(?User $user, ?string $title, ?string $slug, ?string $body): Post
    {
        $post = new self();
        $post->user = $user;
        $post->title = $title;
        $post->slug = $title;
        $post->body = $body;
        $post->updated_at = new \DateTimeImmutable('now', new \DateTimeZone('Europe/Paris'));
        $post->status = self::DRAFT;
        return $post;
    }

    /**
     * @param User $user
     * @param string $title
     * @param string $slug
     * @param string $body
     * @return Post
     */
    public static function fromPost(User $user, string $title, string $slug, string $body): Post
    {
        $post = new self();
        $post->user = $user;
        $post->title = $title;
        $post->slug = $slug;
        $post->body = $body;
        $post->updated_at = new \DateTimeImmutable('now', new \DateTimeZone('Europe/Paris'));
        $post->status= self::PUBLISHED;
        return $post;
    }
    /**
     * @return uid
     */
    public function getId(): ?uuid
    {
        return $this->id;
    }

    /**
     * @param $title
     */
	public function setBody($body): void
    {
        $this->body = $body;
    }
    /**
     * @param $title
     */
	public function setTitle($title): void
    {
        $this->title = $title;
    }
	/**
	 *@return mixed
	 */
	 public function getTitle(): ?string
	 {
		 return $this->title;
	 }

	 /**
	 *@return mixed
	 */
	 public function getBody()
	 {
		 return $this->body;
	 }

	 /**
	 *@return mixed
	 */
	 public function getSlug()
	 {
		 return $this->slug;
	 }

	 /**
	 *@return \DateTimeInterface
	 */
	 public function getCreatedAt(): ?\DateTimeInterface
	 {
		 return $this->created_at;
	 }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }


    /**
     * @param \App\Entity\Comment $comment
     */
     public function addComment(Comment $comment)
     {
        $comment->setPost($this);
        if(!$this->comments->contains($comment)){
            $this->comments->add($comment);
        }
     }

    /**
     * @param \App\Entity\Comment $comment
     */
	 public function deleteComment(Comment $comment)
     {
        $this->comments->remove($comment);
     }

    /**
     * @return ArrayCollection|Comment[]
     */
     public function getComments()
     {
        return $this->comments;
     }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }
    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param $slug
     */
    public function setSlug($slug): void
    {
        $this->slug = $slug;
    }

}
