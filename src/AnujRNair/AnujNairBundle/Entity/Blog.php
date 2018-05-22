<?php

namespace AnujRNair\AnujNairBundle\Entity;

use JsonSerializable;
use AnujRNair\AnujNairBundle\Helper\PostHelper;
use AnujRNair\AnujNairBundle\Helper\URLHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;

/**
 * Blog
 *
 * @ORM\Table()
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="AnujRNair\AnujNairBundle\Repository\BlogRepository")
 */
class Blog implements JsonSerializable
{
    /**
     * @var integer
     * @ORM\Column(name="id", type="integer", options={"unsigned" = true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="AnujRNair\AnujNairBundle\Entity\User", inversedBy="blogPosts")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;

    /**
     * @var TagMap[]
     * @ORM\OneToMany(targetEntity="AnujRNair\AnujNairBundle\Entity\TagMap", mappedBy="blog")
     */
    private $tagMap;

    /**
     * @var Comment[]
     * @ORM\OneToMany(targetEntity="AnujRNair\AnujNairBundle\Entity\Comment", mappedBy="blog")
     * @ORM\OrderBy({"datePosted" = "ASC"})
     */
    private $comments;

    /**
     * @var string
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(name="contents", type="string", length=8000)
     */
    private $contents;

    /**
     * @var \DateTime
     * @ORM\Column(name="date_published", type="datetimetz")
     */
    private $datePublished;

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
     * @var boolean
     * @ORM\Column(name="has_comments", type="boolean", options={"default" = 1})
     */
    private $hasComments;


    /**
     * Set up the One to Many relationships
     */
    public function __construct()
    {
        $this->tagMap = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    /**
     * Get id of blog
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user
     * @param User $user
     * @return Blog
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
     * Set title
     * @param string $title
     * @return Blog
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get title
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Get URL safe title
     * @return string
     */
    public function getUrlSafeTitle()
    {
        return URLHelper::getURLSafeString($this->title);
    }

    /**
     * Set contents
     * @param string $contents
     * @return Blog
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
        return PostHelper::parseBBCode($this->contents);
    }

    /**
     * Get contents which has been parsed, but has no HTML
     * @param int $length
     * @param string $truncationIndicator
     * @return string
     */
    public function getNoHTMLAbstract($length = 150, $truncationIndicator = '...')
    {
        return PostHelper::safeShorten(PostHelper::stripBBCode($this->contents), $length, $truncationIndicator);
    }

    /**
     * Get the abstract of a post, which has been parsed
     * @param int $length
     * @param string $truncationIndicator
     * @return string
     */
    public function getAbstract($length = 500, $truncationIndicator = '...')
    {
        return PostHelper::safeShorten(PostHelper::parseBBCode($this->contents), $length, $truncationIndicator);
    }

    /**
     * Set datePublished
     * @param \DateTime $datePublished
     * @return Blog
     */
    public function setDatePublished($datePublished)
    {
        $this->datePublished = $datePublished;
        return $this;
    }

    /**
     * Get datePublished
     * @return \DateTime
     */
    public function getDatePublished()
    {
        return $this->datePublished;
    }

    /**
     * Set dateUpdated
     * @param \DateTime $dateUpdated
     * @return Blog
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
     * @return Blog
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
     * Set hasComments
     * @param boolean $hasComments
     * @return Blog
     */
    public function setHasComments($hasComments)
    {
        $this->hasComments = $hasComments;
        return $this;
    }

    /**
     * Get Comments
     * @return boolean
     */
    public function hasComments()
    {
        return $this->hasComments;
    }

    /**
     * Add TagMap
     * @param \AnujRNair\AnujNairBundle\Entity\TagMap $tagMap
     * @return Blog
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
     * Get Tags for the blog post
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
     * Add comment
     * @param \AnujRNair\AnujNairBundle\Entity\Comment $comment
     * @return Blog
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
     * Get all comments
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
     * Count comments
     * @return int
     */
    public function countComments()
    {
        return $this->comments->count();
    }

    /**
     * Count comments
     * @return int
     */
    public function countVisibleComments()
    {
        $criteria = Criteria::create();
        $criteria->where(Criteria::expr()->neq('deleted', true));
        return $this->comments->matching($criteria)->count();
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->datePublished = new \DateTime();
        $this->deleted = false;
        $this->hasComments = true;
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
            'id' => $this->id,
            'userId' => $this->user->getId(),
            'tagMap' => $this->tagMap,
            'title' => $this->title,
            'contents' => $this->contents,
            'datePublished' => $this->datePublished,
            'dateUpdated' => $this->dateUpdated
        ];
    }

}
