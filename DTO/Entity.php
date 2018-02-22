<?php

declare(strict_types=1);

namespace MovingImage\Bundle\MICommentsBundle\DTO;

use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Entity DTO.
 * This object is embedded into Comment DTO.
 *
 * @JMS\ExclusionPolicy("all")
 */
class Entity
{
    /**
     * @var string
     * @JMS\Expose()
     * @JMS\Type("string")
     * @Assert\NotBlank()
     */
    private $id;

    /**
     * @var string
     * @JMS\Expose()
     * @JMS\Type("string")
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * Entity constructor.
     *
     * @param string $id
     * @param string $title
     */
    public function __construct(?string $id, ?string $title)
    {
        $this->id = $id;
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }
}
