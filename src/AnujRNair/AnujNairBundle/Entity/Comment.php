<?php

namespace AnujRNair\AnujNairBundle\Entity;

use AnujRNair\AnujNairBundle\Helper\PostHelper;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Comment
 *
 * @ORM\Table()
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="AnujRNair\AnujNairBundle\Repository\CommentRepository")
 */
class Comment
{
    /**
     * @var integer
     * @ORM\Column(name="id", type="integer", options={"unsigned" = true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Blog
     * @ORM\ManyToOne(targetEntity="AnujRNair\AnujNairBundle\Entity\Blog", inversedBy="comments")
     * @ORM\JoinColumn(name="blog_id", referencedColumnName="id", nullable=false)
     */
    private $blog;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="AnujRNair\AnujNairBundle\Entity\User", inversedBy="comments")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    private $user;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     * @Assert\NotBlank()
     * @Assert\Length(
     *  min="3",
     *  max="100",
     *  minMessage="Your name should have 3 characters or more",
     *  maxMessage="Your name should have 100 characters or less"
     * )
     * @Assert\Regex(
     *  "/^[a-z\'\-\s]+$/",
     *  message="Your name should only contain alpha characters!"
     * )
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(name="comment", type="string", length=8000)
     * @Assert\NotBlank()
     * @Assert\Length(
     *  min="10",
     *  max="8000",
     *  minMessage="Your comment should have 10 characters or more",
     *  maxMessage="Your comment should have 8000 characters or less"
     * )
     */
    private $comment;

    /**
     * @var \DateTime
     * @ORM\Column(name="date_posted", type="datetimetz")
     */
    private $datePosted;

    /**
     * @var \DateTime
     * @ORM\Column(name="date_updated", type="datetimetz", nullable=true)
     */
    private $dateUpdated;

    /**
     * @var boolean
     * @ORM\Column(name="is_deleted", type="boolean", options={"default" = 0})
     */
    private $deleted;


    /**
     * Get id of comment
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set blog
     * @param Blog $blog
     * @return Comment
     */
    public function setBlog($blog)
    {
        $this->blog = $blog;
        return $this;
    }

    /**
     * Get blog
     * @return Blog
     */
    public function getBlog()
    {
        return $this->blog;
    }

    /**
     * Set user
     * @param User $user
     * @return Comment
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get user
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set name
     * @param string $name
     * @return Comment
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set comment
     * @param string $comment
     * @return Comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * Get raw comment which has not been parsed
     * @return string
     */
    public function getRawComment()
    {
        return $this->comment;
    }

    /**
     * Get comment which has been parsed
     * @return string
     */
    public function getComment()
    {
        return PostHelper::parseBBCode($this->comment);
    }

    /**
     * Set datePosted
     * @param \DateTime $datePosted
     * @return Comment
     */
    public function setDatePosted($datePosted)
    {
        $this->datePosted = $datePosted;
        return $this;
    }

    /**
     * Get datePosted
     * @return \DateTime
     */
    public function getDatePosted()
    {
        return $this->datePosted;
    }

    /**
     * Set dateUpdated
     * @param \DateTime $dateUpdated
     * @return Comment
     */
    public function setDateUpdated($dateUpdated)
    {
        $this->dateUpdated = $dateUpdated;
        return $this;
    }

    /**
     * Get dateUpdated
     * @return \DateTime
     */
    public function getDateUpdated()
    {
        return $this->dateUpdated;
    }

    /**
     * Set deleted
     * @param boolean $deleted
     * @return Comment
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
        return $this;
    }

    /**
     * Get deleted
     * @return boolean
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->datePosted = new \DateTime();
        $this->deleted = false;
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->dateUpdated = new \DateTime();
    }
}
