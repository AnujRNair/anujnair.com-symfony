<?php

namespace AnujRNair\AnujNairBundle\Entity;

use JsonSerializable;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * TagMap
 *
 * @ORM\Table(name="tag_map", uniqueConstraints={@UniqueConstraint(name="tag_blog_unique", columns={"tag_id", "blog_id"}), @UniqueConstraint(name="tag_portfolio_unique", columns={"tag_id", "portfolio_id"})})
 * @ORM\Entity
 */
class TagMap implements JsonSerializable
{

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Tag
     * @ORM\ManyToOne(targetEntity="AnujRNair\AnujNairBundle\Entity\Tag", inversedBy="tagMap")
     * @ORM\JoinColumn(name="tag_id", referencedColumnName="id", nullable=false)
     */
    private $tag;

    /**
     * @var Blog
     * @ORM\ManyToOne(targetEntity="AnujRNair\AnujNairBundle\Entity\Blog", inversedBy="tagMap")
     * @ORM\JoinColumn(name="blog_id", referencedColumnName="id", nullable=true)
     */
    private $blog;

    /**
     * @var Portfolio
     * @ORM\ManyToOne(targetEntity="AnujRNair\AnujNairBundle\Entity\Portfolio", inversedBy="tagMap")
     * @ORM\JoinColumn(name="portfolio_id", referencedColumnName="id", nullable=true)
     */
    private $portfolio;


    /**
     * Get id
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set tag
     * @param Tag $tag
     * @return TagMap
     */
    public function setTag($tag)
    {
        $this->tag = $tag;
        return $this;
    }

    /**
     * Get tag
     * @return Tag
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Set blog
     * @param Blog $blog
     * @return TagMap
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
     * Set portfolio
     * @param Portfolio $portfolio
     * @return TagMap
     */
    public function setPortfolio($portfolio)
    {
        $this->portfolio = $portfolio;
        return $this;
    }

    /**
     * Get portfolio
     * @return Portfolio
     */
    public function getPortfolio()
    {
        return $this->portfolio;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'tag' => $this->tag,
            'blog' => $this->blog,
            'portfolio' => $this->portfolio
        ];
    }
}
