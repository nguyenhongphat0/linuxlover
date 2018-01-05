<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Post;
use App\Entity\PostContent;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PostController extends Controller
{
    /**
     * @Route("/post", name="posts")
     */
    public function index()
    {
        $repo = $this->getDoctrine()->getRepository(Post::class);
        $posts = $repo->findAll();
        return $this->render('posts/index.html.twig', ['posts' => $posts]);
    }

    /**
     * @Route("/post/{id}", name="showpost", requirements={"id"="\d+"})
     */
    public function showPost(Post $post, PostContent $postContent) {
        $comments = $post->getComments();
        return $this->render("posts/post.html.twig", [
            'post' => $post,
            'content' => $postContent,
            'comments' => $comments
        ]);
    }

    /**
     * @Route("/post/create", name="createpost", methods="GET")
     */
    public function createPost() {
        return $this->render('posts/form.html.twig', [
            'title' => '',
            'tag' => '',
            'content' => ''
        ]);
    }

    /**
     * @Route("/post/create", name="addpost", methods="POST")
     */
    public function addPost(Request $request) {
        // Get post information
        $title = $request->get('title');
        $tag = $request->get('tag');
        $postedAt = new \DateTime();
        $content = $request->get('content');

        // Get entity manager
        $em = $this->getDoctrine()->getManager();

        // Push post info to database
        $post = new Post();
        $post->setTitle($title);
        $post->setTag($tag);
        $post->setPostedAt($postedAt);
        $em->persist($post);
        $em->flush();

        // Push post content to database
        $pc = new PostContent();
        $pc->setPostId($post->getId());
        $pc->setContent($content);
        $em->persist($pc);
        $em->flush();

        // Finish, return to posts view
        return $this->redirectToRoute('posts');
    }


    /**
     * @Route("/post/edit/{id}", name="editpost", methods="GET")
     */
    public function editPost(Post $post, PostContent $postContent) {
        return $this->render('posts/form.html.twig', [
            'title' => $post->getTitle(),
            'tag' => $post->getTag(),
            'content' => $postContent->getContent()
        ]);
    }

    /**
     * @Route("/post/edit/{id}", name="updatepost", methods="POST")
     */
    public function updatePost(Request $request, Post $post, PostContent $postContent) {
        // Edit post
        $post->setTitle($request->get('title'));
        $post->setTag($request->get('tag'));
        $post->setPostedAt(new \DateTime());
        $postContent->setContent($request->get('content'));

        // Submit
        $this->getDoctrine()->getManager()->flush();

        // Show edited post
        return $this->redirectToRoute('showpost', ['id' => $post->getId()]);
    }

    /**
     * @Route("/post/delete/{id}", name="deletepost")
     */
    public function deletePost(Post $post, PostContent $postContent) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->remove($postContent);
        $em->flush();
        $this->addFlash('notice', $post->getTitle().' removed successfuly');
        return $this->redirectToRoute('posts');
    }
}
