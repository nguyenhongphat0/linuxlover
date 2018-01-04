<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="comments")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $post;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="replyTo")
     */
    private $replies;

    /**
     * @ORM\ManyToOne(targetEntity="Comment", inversedBy="replies")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private $replyTo;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="comments")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $user;

    /**
     * @ORM\Column(type="string", columnDefinition="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $commentAt;

    public function __construct()
    {
        $this->replies = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getPost(): Post
    {
        return $this->post;
    }

    /**
     * @param mixed $postId
     */
    public function setPost(Post $post): void
    {
        $this->post = $post;
    }

    /**
     * @return mixed
     */
    public function getReplies()
    {
        return $this->replies;
    }

    /**
     * @param mixed $replies
     */
    public function setReplies($replies): void
    {
        $this->replies = $replies;
    }

    public function isReply() {
        return $this->replyTo != null;
    }

    public function getReplyTo(): Comment
    {
        return $this->replyTo;
    }

    /**
     * @param mixed $replyTo
     */
    public function setReplyTo(Comment $replyTo): void
    {
        $this->replyTo = $replyTo;
    }

    /**
     * @return mixed
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content): void
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getCommentAt()
    {
        return $this->commentAt;
    }

    /**
     * @param mixed $commentAt
     */
    public function setCommentAt($commentAt): void
    {
        $this->commentAt = $commentAt;
    }
}