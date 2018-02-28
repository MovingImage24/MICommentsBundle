<?php

declare(strict_types=1);

namespace MovingImage\Bundle\MICommentsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTime;
use DateTimeZone;

/**
 * Comment Entity.
 *
 * @ORM\Table(name="mi_comments_bundle_comment",indexes={@ORM\Index(name="entityId", columns={"entityId"})})
 * @ORM\Entity(repositoryClass="MovingImage\Bundle\MICommentsBundle\Repository\CommentRepository")
 */
class Comment
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="entityId", type="string", length=255)
     */
    private $entityId;

    /**
     * @var string
     *
     * @ORM\Column(name="entityTitle", type="text", nullable=true)
     */
    private $entityTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="userName", type="text")
     */
    private $userName;

    /**
     * @var string
     *
     * @ORM\Column(name="userEmail", type="text")
     */
    private $userEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text")
     */
    private $comment;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="dateCreated", type="datetime")
     */
    private $dateCreated;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="datePublished", type="datetime", nullable=true)
     */
    private $datePublished;

    /**
     * @param DateTime $dateCreated
     */
    public function __construct(DateTime $dateCreated = null)
    {
        if (is_null($dateCreated)) {
            $dateCreated = new DateTime();
        }

        //TODO make this configurable in bundle config
        $dateCreated->setTimezone(new DateTimeZone('UTC'));
        $this->dateCreated = $dateCreated;
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param string $entityId
     *
     * @return Comment
     */
    public function setEntityId(string $entityId): self
    {
        $this->entityId = $entityId;

        return $this;
    }

    /**
     * @return string
     */
    public function getEntityId(): ?string
    {
        return $this->entityId;
    }

    /**
     * @param string $entityTitle
     *
     * @return Comment
     */
    public function setEntityTitle(?string $entityTitle): self
    {
        $this->entityTitle = $entityTitle;

        return $this;
    }

    /**
     * @return string
     */
    public function getEntityTitle(): ?string
    {
        return $this->entityTitle;
    }

    /**
     * @param string $userName
     *
     * @return Comment
     */
    public function setUserName(string $userName): self
    {
        $this->userName = $userName;

        return $this;
    }

    /**
     * @return string
     */
    public function getUserName(): ?string
    {
        return $this->userName;
    }

    /**
     * @param string $userEmail
     *
     * @return Comment
     */
    public function setUserEmail(string $userEmail): self
    {
        $this->userEmail = $userEmail;

        return $this;
    }

    /**
     * @return string
     */
    public function getUserEmail(): ?string
    {
        return $this->userEmail;
    }

    /**
     * @param string $comment
     *
     * @return Comment
     */
    public function setComment($comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return string
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @return DateTime
     */
    public function getDateCreated(): ?DateTime
    {
        return $this->dateCreated;
    }

    /**
     * @param DateTime $dateCreated
     *
     * @return Comment
     */
    public function setDateCreated(DateTime $dateCreated): self
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDatePublished(): ?DateTime
    {
        return $this->datePublished;
    }

    /**
     * @param DateTime $datePublished
     *
     * @return Comment
     */
    public function setDatePublished(DateTime $datePublished): self
    {
        $this->datePublished = $datePublished;

        return $this;
    }
}
