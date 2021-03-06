<?php

declare(strict_types=1);

namespace MovingImage\Bundle\MICommentsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * Comment Entity.
 *
 * @ORM\Table(name="mi_comments_bundle_comment",indexes={
 *     @ORM\Index(name="entityId", columns={"entityId"}),
 *     @ORM\Index(name="status", columns={"status"})
 * })
 * @ORM\Entity(repositoryClass="MovingImage\Bundle\MICommentsBundle\Repository\CommentRepository")
 */
class Comment
{
    const STATUS_PENDING = 'pending';
    const STATUS_PUBLISHED = 'published';
    const STATUS_REJECTED = 'rejected';

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
     * @ORM\Column(name="userEmail", type="text", nullable=true)
     */
    private $userEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text")
     */
    private $comment;

    /**
     * @var string
     *
     * @ORM\Column(name="administratorReply", type="text", nullable=true)
     */
    private $administratorReply;

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
     * @var DateTime
     *
     * @ORM\Column(name="dateRejected", type="datetime", nullable=true)
     */
    private $dateRejected;

    /**
     * @var string
     *
     * @ORM\Column(type="string", options={"default" = "pending"}, columnDefinition="ENUM('pending', 'rejected', 'published')")
     */
    private $status;

    /**
     * @param DateTime $dateCreated
     */
    public function __construct(DateTime $dateCreated = null)
    {
        if (is_null($dateCreated)) {
            $dateCreated = new DateTime();
        }

        $this->dateCreated = $dateCreated;
        $this->status = self::STATUS_PENDING;
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
     * @param null|string $administratorReply
     *
     * @return Comment
     */
    public function setAdministratorReply(?string $administratorReply): self
    {
        $this->administratorReply = $administratorReply;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getAdministratorReply(): ?string
    {
        return $this->administratorReply;
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

    /**
     * @return DateTime
     */
    public function getDateRejected(): DateTime
    {
        return $this->dateRejected;
    }

    /**
     * @param DateTime $dateRejected
     *
     * @return Comment
     */
    public function setDateRejected(DateTime $dateRejected): self
    {
        $this->dateRejected = $dateRejected;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @return Comment
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
