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
    public function showPost(Post $post) {
        $repo = $this->getDoctrine()->getRepository(PostContent::class);
        $content = $repo->find($post->getId());
        return $this->render("posts/post.html.twig", [
            'post' => $post,
            'content' => $content
        ]);
    }

    /**
     * @Route("/post/create", name="createpost", methods="GET")
     */
    public function createPost(SessionInterface $session) {
        if (!$session->get('user'))
            throw $this->createNotFoundException();
        return $this->render('posts/form.html.twig', [
            'title' => '',
            'tag' => '',
            'content' => ''
        ]);
    }

    /**
     * @Route("/post/create", name="addpost", methods="POST")
     */
    public function addPost(Request $request, SessionInterface $session) {
        if (!$session->get('user'))
            throw $this->createNotFoundException();
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
    public function editPost($id, SessionInterface $session) {
        if (!$session->get('user'))
            throw $this->createNotFoundException();
        // Get repo
        $postRepo = $this->getDoctrine()->getRepository(Post::class);
        $contentRepo = $this->getDoctrine()->getRepository(PostContent::class);

        // Get post and content by ID
        $post = $postRepo->find($id);
        $content = $contentRepo->find($id);

        return $this->render('posts/form.html.twig', [
            'title' => $post->getTitle(),
            'tag' => $post->getTag(),
            'content' => $content->getContent()
        ]);
    }

    /**
     * @Route("/post/edit/{id}", name="updatepost", methods="POST")
     */
    public function updatePost(Request $request, SessionInterface $session, $id) {
        if (!$session->get('user'))
            throw $this->createNotFoundException();
        // Get repo
        $postRepo = $this->getDoctrine()->getRepository(Post::class);
        $contentRepo = $this->getDoctrine()->getRepository(PostContent::class);

        // Get post and content by ID
        $post = $postRepo->find($id);
        $content = $contentRepo->find($id);

        // Edit post
        $post->setTitle($request->get('title'));
        $post->setTag($request->get('tag'));
        $post->setPostedAt(new \DateTime());
        $content->setContent($request->get('content'));

        // Submit
        $this->getDoctrine()->getManager()->flush();

        // Show edited post
        return $this->redirectToRoute('showpost', ['id' => $id]);
    }

    /**
     * @Route("/post/delete/{id}", name="deletepost")
     */
    public function deletePost(SessionInterface $session, $id) {
        if (!$session->get('user'))
            throw $this->createNotFoundException();
        $em = $this->getDoctrine()->getManager();
        $postRepo = $this->getDoctrine()->getRepository(Post::class);
        $contentRepo = $this->getDoctrine()->getRepository(PostContent::class);
        $post = $postRepo->find($id);
        $content = $contentRepo->find($id);
        $em->remove($post);
        $em->remove($content);
        $em->flush();
        $this->addFlash('notice', $post->getTitle().' removed successfuly');
        return $this->redirectToRoute('posts');
    }
}
