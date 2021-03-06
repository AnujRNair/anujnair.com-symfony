<?php

namespace AnujRNair\AnujNairBundle\Entity;

use Parsedown;
use JsonSerializable;
use AnujRNair\AnujNairBundle\Helper\PostHelper;
use AnujRNair\AnujNairBundle\Helper\URLHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Portfolio
 *
 * @ORM\Table()
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="AnujRNair\AnujNairBundle\Repository\PortfolioRepository")
 */
class Portfolio implements JsonSerializable
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
     * @var Parsedown
     */
    private $parsedown = null;


    /**
     * Set up the One to Many relationships
     */
    public function __construct()
    {
        $this->tagMap = new ArrayCollection();
    }

    /**
     * Get the Parsedown instance
     * @return Parsedown
     */
    private function getParsedown() {
        if ($this->parsedown === null) {
            $this->parsedown = new Parsedown();
        }

        return $this->parsedown;
    }

    /**
     * Get id of portfolio
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
     * Get URL safe title
     * @return string
     */
    public function getUrlSafeName()
    {
        return URLHelper::getURLSafeString($this->name);
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
     * Get raw contents which has not been parsed
     * @return string
     */
    public function getRawContents()
    {
        return $this->contents;
    }

    /**
     * Get contents which has been parsed
     * @return string
     */
    public function getContents()
    {
        return $this->getParsedown()->text($this->contents);
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
     * @param string $format
     * @return string
     */
    public function getDateCreated($format = 'jS F Y')
    {
        if ($this->dateCreated instanceof \DateTime) {
            return $this->dateCreated->format($format);
        }

        return null;
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
     * @param string $format
     * @return string
     */
    public function getDateUpdated($format = 'jS F Y')
    {
        if ($this->dateUpdated instanceof \DateTime) {
            return $this->dateUpdated->format($format);
        }

        return null;
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
     * @return Portfolio
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
     * Get Tags for the portfolio
     * @return Tag[]
     */
    public function getTags()
    {
        $tags = [];
        foreach ($this->tagMap as $map) {
            $tags[] = $map->getTag();
        }
        return $tags;
    }

    /**
     * Get Tags for the blog post
     * @return Integer[]
     */
    public function getTagIds()
    {
        $tagIds = [];
        foreach ($this->tagMap as $map) {
            $tagIds[] = $map->getTag()->getId();
        }
        return $tagIds;
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

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'contents' => $this->getContents(),
            'image' => $this->getImage(),
            'link' => $this->getLink(),
            'dateCreated' => $this->getDateCreated(),
            'tagIds' => $this->getTagIds(),
            'urlTitle' => $this->getUrlSafeName()
        ];
    }
}
