<?php

namespace AnujRNair\AnujNairBundle\Entity;

use JsonSerializable;
use AnujRNair\AnujNairBundle\Helper\URLHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * Tag
 *
 * @ORM\Table(name="tag", uniqueConstraints={@UniqueConstraint(name="name_unique", columns={"name"})})
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="AnujRNair\AnujNairBundle\Repository\TagRepository")
 */
class Tag implements JsonSerializable
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
     * @ORM\OneToMany(targetEntity="AnujRNair\AnujNairBundle\Entity\TagMap", mappedBy="tag")
     */
    private $tagMap;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var \DateTime
     * @ORM\Column(name="date_created", type="datetimetz")
     */
    private $dateCreated;

    /**
     * @var boolean
     * @ORM\Column(name="is_deleted", type="boolean", options={"default" = 0})
     */
    private $deleted;


    /**
     * Set up One to Many relationships
     */
    public function __construct()
    {
        $this->tagMap = new ArrayCollection();
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
     * @return Tag
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
     * Get URL safe name
     * @return string
     */
    public function getUrlSafeName()
    {
        return URLHelper::getURLSafeString($this->name);
    }

    /**
     * Set dateCreated
     * @param \DateTime $dateCreated
     * @return Tag
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
     * Set deleted
     * @param boolean $deleted
     * @return Tag
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

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }
}
