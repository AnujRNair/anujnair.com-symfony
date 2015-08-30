<?php

namespace AnujRNair\AnujNairBundle\Entity;

use AnujRNair\AnujNairBundle\Helper\BBCodeHelper;
use Doctrine\ORM\Mapping as ORM;

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
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(name="comment", type="string", length=8000)
     */
    private $comment;

    /**
     * @var \DateTime
     * @ORM\Column(name="date_posted", type="datetimetz")
     */
    private $datePosted;

    /**
     * @var \DateTime
     * @ORM\Column(name="date_updated", type="datetimetz")
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
        return BBCodeHelper::parseBBCode($this->comment);
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
    protected function prePersist()
    {
        $this->datePosted = new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    protected function preUpdate()
    {
        $this->dateUpdated = new \DateTime();
    }
}
