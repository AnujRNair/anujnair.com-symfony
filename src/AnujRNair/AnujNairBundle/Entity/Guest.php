<?php

namespace AnujRNair\AnujNairBundle\Entity;

use JsonSerializable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Guest
 *
 * @ORM\Table(name="guest", uniqueConstraints={@UniqueConstraint(name="name_unique", columns={"name", "ip_created", "ip_last_visited", "useragent"})})
 * @ORM\Entity(repositoryClass="AnujRNair\AnujNairBundle\Repository\GuestRepository")
 */
class Guest implements JsonSerializable
{
    /**
     * @var integer
     * @ORM\Column(name="id", type="integer", options={"unsigned" = true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Comment[]
     * @ORM\OneToMany(targetEntity="AnujRNair\AnujNairBundle\Entity\Comment", mappedBy="guest")
     * @ORM\OrderBy({"datePosted" = "ASC"})
     */
    private $comments;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(
     *  min="3",
     *  max="100",
     *  minMessage="Your name should have 3 characters or more",
     *  maxMessage="Your name should have 100 characters or less"
     * )
     * @Assert\Regex(
     *  "/^[a-z\'\-\s]+$/i",
     *  message="Your name should only contain alpha characters!"
     * )
     */
    private $name;

    /**
     * @var integer
     * @ORM\Column(name="ip_created", type="bigint")
     */
    private $ipCreated;

    /**
     * @var integer
     * @ORM\Column(name="ip_last_visited", type="bigint")
     */
    private $ipLastVisited;

    /**
     * @var \DateTime
     * @ORM\Column(name="date_created", type="datetimetz")
     */
    private $dateCreated;

    /**
     * @var \DateTime
     * @ORM\Column(name="date_last_visited", type="datetimetz")
     */
    private $dateLastVisited;

    /**
     * @var string
     * @ORM\Column(name="useragent", type="string", length=255)
     */
    private $userAgent;


    /**
     * Set up One to Many relationships
     */
    public function __construct()
    {
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
     * Set name
     * @param string $name
     * @return Guest
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
     * Set ipCreated
     * @param integer $ip
     * @return Guest
     */
    public function setIpCreated($ip)
    {
        $this->ipCreated = ip2long($ip);
        return $this;
    }

    /**
     * Get ipCreated
     * @return integer
     */
    public function getIpCreated()
    {
        return long2ip($this->ipCreated);
    }

    /**
     * Set ipLastVisited
     * @param integer $ipLastVisited
     * @return Guest
     */
    public function setIpLastVisited($ipLastVisited)
    {
        $this->ipLastVisited = ip2long($ipLastVisited);
        return $this;
    }

    /**
     * Get ipLastVisited
     * @return integer
     */
    public function getIpLastVisited()
    {
        return long2ip($this->ipLastVisited);
    }

    /**
     * Set userAgent;
     * @param string $userAgent
     * @return Guest
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;
        return $this;
    }

    /**
     * Get userAgent;
     * @return string
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * Set dateCreated
     * @param \DateTime $dateCreated
     * @return Guest
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
        return $this;
    }

    /**
     * Get dateCreated
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Set dateLastVisited
     * @param \DateTime $dateLastVisited
     * @return Guest
     */
    public function setDateLastVisited($dateLastVisited)
    {
        $this->dateLastVisited = $dateLastVisited;
        return $this;
    }

    /**
     * Get dateLastVisited
     * @return \DateTime
     */
    public function getDateLastVisited()
    {
        return $this->dateLastVisited;
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
        $this->dateCreated = new \DateTime();
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName()
        ];
    }
}
