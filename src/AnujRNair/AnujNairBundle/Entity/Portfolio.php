<?php

namespace AnujRNair\AnujNairBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Portfolio
 *
 * @ORM\Table()
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="AnujRNair\AnujNairBundle\Repository\PortfolioRepository")
 */
class Portfolio
{
    /**
     * @var integer
     * @ORM\Column(name="id", type="integer", options={"unsigned" = true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var TagMap[]
     * @ORM\OneToMany(targetEntity="AnujRNair\AnujNairBundle\Entity\TagMap", mappedBy="portfolio")
     */
    private $tagMap;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(name="abstract", type="string", length=300)
     */
    private $abstract;

    /**
     * @var string
     * @ORM\Column(name="contents", type="string", length=2000)
     */
    private $contents;

    /**
     * @var string
     * @ORM\Column(name="image", type="string", length=255)
     */
    private $image;

    /**
     * @var string
     * @ORM\Column(name="link", type="string", length=255)
     */
    private $link;

    /**
     * @var \DateTime
     * @ORM\Column(name="date_created", type="datetimetz")
     */
    private $dateCreated;

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
     * Set up the One to Many relationships
     */
    public function __construct()
    {
        $this->tagMap = new ArrayCollection();
    }

    /**
     * Get id of pprtfolio
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     * @param string $name
     * @return Portfolio
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
     * Set abstract
     * @param string $abstract
     * @return Portfolio
     */
    public function setAbstract($abstract)
    {
        $this->abstract = $abstract;
        return $this;
    }

    /**
     * Get abstract
     * @return string
     */
    public function getAbstract()
    {
        return $this->abstract;
    }

    /**
     * Set contents
     * @param string $contents
     * @return Portfolio
     */
    public function setContents($contents)
    {
        $this->contents = $contents;
        return $this;
    }

    /**
     * Get contents
     * @return string
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * Set image
     * @param string $image
     * @return Portfolio
     */
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * Get image
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set link
     * @param string $link
     * @return Portfolio
     */
    public function setLink($link)
    {
        $this->link = $link;
        return $this;
    }

    /**
     * Get link
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set dateCreated
     * @param \DateTime $dateCreated
     * @return Portfolio
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
     * Set dateUpdated
     * @param \DateTime $dateUpdated
     * @return Portfolio
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
     * @return Portfolio
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
        return $this;
    }

    /**
     * Get isDeleted
     * @return boolean
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Add TagMap
     * @param \AnujRNair\AnujNairBundle\Entity\TagMap $tagMap
     * @return User
     */
    public function addTagMap(TagMap $tagMap)
    {
        $this->tagMap[] = $tagMap;
        return $this;
    }

    /**
     * Remove TagMap
     * @param \AnujRNair\AnujNairBundle\Entity\TagMap $tagMap
     */
    public function removeTagMap(TagMap $tagMap)
    {
        $this->tagMap->removeElement($tagMap);
    }

    /**
     * Get blogPosts
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTagMap()
    {
        return $this->tagMap;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->dateCreated = new \DateTime();
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
