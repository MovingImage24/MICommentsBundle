<?php

declare(strict_types=1);

namespace MovingImage\Bundle\MICommentsBundle\DTO;

use JMS\Serializer\Annotation as JMS;

/**
 * CommentsCollection DTO.
 *
 * @JMS\ExclusionPolicy("all")
 */
class CommentsCollection
{
    /**
     * @JMS\Type("integer")
     * @JMS\SerializedName("total")
     * @JMS\Expose()
     *
     * @var int
     */
    private $totalCount;

    /**
     * @JMS\Type("integer")
     * @JMS\Expose()
     *
     * @var int
     */
    private $limit;

    /**
     * @JMS\Type("integer")
     * @JMS\Expose()
     *
     * @var int
     */
    private $offset;

    /**
     * @JMS\Type("array<MovingImage\Bundle\MICommentsBundle\DTO\Comment>")
     * @JMS\Expose()
     *
     * @var Comment[]
     */
    private $comments;

    /**
     * CommentsCollection constructor.
     *
     * @param int       $totalCount
     * @param Comment[] $comments
     * @param int       $limit
     * @param int       $offset
     */
    public function __construct(int $totalCount, array $comments, int $limit, int $offset)
    {
        $this->totalCount = $totalCount;
        $this->comments = $comments;
        $this->limit = $limit;
        $this->offset = $offset;
    }

    /**
     * @return int
     */
    public function getTotalCount(): int
    {
        return $this->totalCount;
    }

    /**
     * @return Comment[]
     */
    public function getComments(): array
    {
        return $this->comments;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }
}
