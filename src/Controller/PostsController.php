<?php
declare(strict_types=1);

namespace App\Controller;


use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PostRepository;
use App\Entity\Post;
use App\Form\PostType;
use App\Entity\Comment;
use App\Form\CommentType;

class PostsController extends AbstractController
{
    /** @var PostRepository */
    private $postRepository;
    public function __construct(PostRepository $postRepository){
        $this->postRepository = $postRepository;
    }
    /**
     * @Route("/posts/search", name="blog_search")
     */
    public function search(Request $request)
    {
        $query = $request->query->get('q');
        $repository = $this->postRepository;
        $posts = $repository->searchByQuery($query);
        return $this->render('posts/query_post.html.twig', [
            'posts' => $posts
        ]);
    }
    /**
     * @Route("/posts", name="blog_posts")
     */
    public function index()
    {
        $posts = $this->postRepository->findAll();
        return $this->render('posts/index.html.twig', [
            'posts' => $posts,
        ]);

    }
    /**
     * @Route("/posts/new", name="new_blog_post")
     */
    public function addPost(Request $request, Slugify $slugify)
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $post->setSlug($slugify->slugify($post->getTitle()));
            //$post->setCreatedAt(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();
            return $this->redirectToRoute('blog_show', [
                'slug' => $post->getSlug()
            ]);
        }
        return $this->render('posts/new.html.twig', [
            'form' => $form->createView(),
        ]);

    }
    /**
     * @Route("/posts/{slug}/delete", name="blog_post_delete")
     */
    public function deletePost(Post $post)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();
        return $this->redirectToRoute('blog_posts');
    }
    /**
     * @Route("/posts/{slug}", methods={"POST"}, name="comment_new")
     */
    public function commentNew(Request $request, Post $post)
    {
        $comment = Comment::create($post);
        //$comment->setUser($user);
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();
            return $this->redirectToRoute('blog_show', ['slug' => $post->getSlug()]);
        }
        return $this->render('posts/show.html.twig',[
            'post' => $post,
            'form' => $form->createView(),
            'comments' => $post->getComments()
        ]);

    }

    /**
     * @Route("/posts/{slug}", methods={"GET"}, name="blog_show")
     */
    public function showPost(Post $post)
    {
        $comment = Comment::create($post);
        $form = $this->createForm(CommentType::class, $comment);

        return $this->render('posts/show.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
            'comments' =>$post->getComments()
        ]);
    }

    /**
     * @Route("/posts/{slug}/edit", name="blog_post_edit")
     */
    public function editPost(Request $request, Slugify $slugify, Post $post)
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $post->setSlug($slugify->slugify($post->getTitle()));
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();
            return $this->redirectToRoute('blog_show', [
                'slug' => $post->getSlug()
            ]);
        }
        return $this->render('posts/new.html.twig', [
            'form' => $form->createView()
        ]);

    }

}
