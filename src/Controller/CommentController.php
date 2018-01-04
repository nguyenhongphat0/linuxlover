<?php
/**
 * Created by PhpStorm.
 * User: nguyenhongphat0
 * Date: 1/4/18
 * Time: 1:46 PM
 */

namespace App\Controller;


use App\Entity\Comment;
use App\Entity\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CommentController extends Controller
{
    /**
     * @Route("/comment/{id}/{to}", name="comment", requirements={"id"="\d+", "to"="\d+"}, defaults={"to"=null}, methods="POST")
     */
    public function comment(Request $request, Post $post, $id, $to) {
        if ($this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED'));
        $content = $request->get('content');
        $commentAt = new \DateTime();
        if ($to)
            $replyTo = $this->getDoctrine()->getRepository(Comment::class)->find($to);
        else
            $replyTo = null;

        $comment = new Comment();
        $comment->setUser($this->getUser());
        $comment->setContent($content);
        $comment->setCommentAt($commentAt);
        $comment->setPost($post);
        if ($replyTo)
            $comment->setReplyTo($replyTo);
        $em = $this->getDoctrine()->getManager();
        $em->persist($comment);
        $em->flush();

        return $this->redirectToRoute('showpost', ['id' => $id]);
    }
}