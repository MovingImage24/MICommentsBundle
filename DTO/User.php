<?php

declare(strict_types=1);

namespace MovingImage\Bundle\MICommentsBundle\DTO;

use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User DTO.
 * This object is embedded into Comment DTO.
 *
 * @JMS\ExclusionPolicy("all")
 */
class User
{
    /**
     * @var string
     * @JMS\Expose()
     * @JMS\Type("string")
     * @Assert\NotBlank()
     */
    private $email;

    /**
     * @var string
     * @JMS\Expose()
     * @JMS\Type("string")
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * User constructor.
     *
     * @param string $email
     * @param string $name
     */
    public function __construct(?string $email, ?string $name)
    {
        $this->email = $email;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }
}
