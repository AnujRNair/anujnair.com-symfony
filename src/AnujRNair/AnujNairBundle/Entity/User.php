<?php

namespace AnujRNair\AnujNairBundle\Entity;

use JsonSerializable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * Users
 *
 * @ORM\Table(name="user", uniqueConstraints={@UniqueConstraint(name="username_unique", columns={"username"}), @UniqueConstraint(name="email_unique", columns={"email"})})
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="AnujRNair\AnujNairBundle\Repository\UserRepository")
 */
class User implements JsonSerializable
{
    /**
     * @var integer
     * @ORM\Column(name="id", type="integer", options={"unsigned" = true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Blog[]
     * @ORM\OneToMany(targetEntity="AnujRNair\AnujNairBundle\Entity\Blog", mappedBy="user")
     * @ORM\OrderBy({"datePublished" = "ASC"})
     */
    private $blogPosts;

    /**
     * @var Comment[]
     * @ORM\OneToMany(targetEntity="AnujRNair\AnujNairBundle\Entity\Comment", mappedBy="user")
     * @ORM\OrderBy({"datePosted" = "ASC"})
     */
    private $comments;

    /**
     * @var string
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $username;

    /**
     * @var string
     * @ORM\Column(name="first_name", type="string", length=255)
     */
    private $firstName;

    /**
     * @var string
     * @ORM\Column(name="last_name", type="string", length=255)
     */
    private $lastName;

    /**
     * @var string
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var integer
     * @ORM\Column(name="registration_ip", type="integer")
     */
    private $registrationIp;

    /**
     * @var \DateTime
     * @ORM\Column(name="date_registered", type="datetimetz")
     */
    private $dateRegistered;

    /**
     * @var boolean
     * @ORM\Column(name="is_active", type="boolean", options={"default" = 1})
     */
    private $active;


    /**
     * Set up One to Many relationships
     */
    public function __construct()
    {
        $this->blogPosts = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    /**
     * Get id
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * Get username
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set firstName
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * Get firstName
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * Get lastName
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set email
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Get email
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Get password
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set registrationIp
     * @param integer $registrationIp
     * @return User
     */
    public function setRegistrationIp($registrationIp)
    {
        $this->registrationIp = ip2long($registrationIp);
        return $this;
    }

    /**
     * Get registrationIp
     * @return integer
     */
    public function getRegistrationIp()
    {
        return long2ip($this->registrationIp);
    }

    /**
     * Set registrationDate
     * @param \DateTime $dateRegistered
     * @return User
     */
    public function setDateRegistered($dateRegistered)
    {
        $this->dateRegistered = $dateRegistered;
        return $this;
    }

    /**
     * Get registrationDate
     * @return \DateTime
     */
    public function getDateRegistered()
    {
        return $this->dateRegistered;
    }

    /**
     * Set active
     * @param boolean $active
     * @return User
     */
    public function setIsActive($active)
    {
        $this->active = $active;
        return $this;
    }

    /**
     * Get active
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * Add blogPosts
     * @param \AnujRNair\AnujNairBundle\Entity\Blog $blogPost
     * @return User
     */
    public function addBlogPost(Blog $blogPost)
    {
        $this->blogPosts[] = $blogPost;
        return $this;
    }

    /**
     * Remove blogPosts
     * @param \AnujRNair\AnujNairBundle\Entity\Blog $blogPost
     */
    public function removeBlogPost(Blog $blogPost)
    {
        $this->blogPosts->removeElement($blogPost);
    }

    /**
     * Get blogPosts
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBlogPosts()
    {
        return $this->blogPosts;
    }

    /**
     * Get visible blog posts
     * @return \Doctrine\Common\Collections\Collection|static
     */
    public function getVisibleBlogPosts()
    {
        $criteria = Criteria::create();
        $criteria->where(Criteria::expr()->neq('deleted', true));
        return $this->blogPosts->matching($criteria);
    }

    /**
     * Add comment
     * @param \AnujRNair\AnujNairBundle\Entity\Comment $comment
     * @return User
     */
    public function addComment(Comment $comment)
    {
        $this->comments[] = $comment;
        return $this;
    }

    /**
     * Remove comment
     * @param \AnujRNair\AnujNairBundle\Entity\Comment $comment
     */
    public function removeComment(Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * Get comments
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Get visible comments
     * @return \Doctrine\Common\Collections\Collection|static
     */
    public function getVisibleComments()
    {
        $criteria = Criteria::create();
        $criteria->where(Criteria::expr()->neq('deleted', true));
        return $this->comments->matching($criteria);
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->dateRegistered = new \DateTime();
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName
        ];
    }
}
