<?php

declare(strict_types=1);

namespace MovingImage\Bundle\MICommentsBundle\DTO;

use MovingImage\Bundle\MICommentsBundle\Entity\Comment as CommentEntity;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;
use DateTime;

/**
 * Comment DTO.
 * This class represents a view of the Comment Entity, suitable for transfer and serialization.
 *
 * @JMS\ExclusionPolicy("all")
 */
class Comment
{
    /**
     * @var int
     * @JMS\Expose()
     * @JMS\Type("integer")
     */
    private $id;

    /**
     * @var User
     * @JMS\Expose()
     * @JMS\Type("MovingImage\Bundle\MICommentsBundle\DTO\User")
     * @Assert\NotBlank()
     * @Assert\Valid()
     */
    private $user;

    /**
     * @var Entity
     * @JMS\Expose()
     * @JMS\Type("MovingImage\Bundle\MICommentsBundle\DTO\Entity")
     * @Assert\NotBlank()
     * @Assert\Valid()
     */
    private $entity;

    /**
     * @var string
     * @JMS\Expose()
     * @JMS\Type("string")
     * @Assert\NotBlank()
     */
    private $comment;

    /**
     * @var DateTime
     * @JMS\Expose()
     * @JMS\Type("DateTime<'Y-m-d H:i:s'>")
     */
    private $dateCreated;

    /**
     * Comment constructor.
     *
     * @param CommentEntity $commentEntity
     */
    public function __construct(CommentEntity $commentEntity)
    {
        $this->id = $commentEntity->getId();
        $this->user = new User($commentEntity->getUserEmail(), $commentEntity->getUserName());
        $this->entity = new Entity($commentEntity->getEntityId(), $commentEntity->getEntityTitle());
        $this->comment = $commentEntity->getComment();
        $this->dateCreated = $commentEntity->getDateCreated();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return Entity
     */
    public function getEntity(): Entity
    {
        return $this->entity;
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
    public function getDateCreated(): DateTime
    {
        return $this->dateCreated;
    }
}
