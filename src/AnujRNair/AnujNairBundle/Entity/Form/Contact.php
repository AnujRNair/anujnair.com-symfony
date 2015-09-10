<?php

namespace AnujRNair\AnujNairBundle\Entity\Form;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Contact
 * @package AnujRNair\AnujNairBundle\Entity\Form
 */
class Contact
{

    /**
     * @var string
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
     * @Assert\Email()
     * @Assert\Length(
     *  min="5",
     *  max="100",
     *  minMessage="Your email should have 5 characters or more",
     *  maxMessage="Your email should have 100 characters or less"
     * )
     */
    private $email;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(
     *  min="5",
     *  max="200",
     *  minMessage="Your subject should have 5 characters or more",
     *  maxMessage="Your subject should have 200 characters or less"
     * )
     */
    private $subject;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(
     *  min="10",
     *  max="1000",
     *  minMessage="Your content should have 10 characters or more",
     *  maxMessage="Your content should have 1000 characters or less"
     * )
     */
    private $contents;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return string
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * @param string $contents
     */
    public function setContents($contents)
    {
        $this->contents = $contents;
    }

}
