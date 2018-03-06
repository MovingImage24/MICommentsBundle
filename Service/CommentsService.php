<?php

declare(strict_types=1);

namespace MovingImage\Bundle\MICommentsBundle\Service;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use MovingImage\Bundle\MICommentsBundle\Entity\Comment;
use MovingImage\Bundle\MICommentsBundle\DTO\CommentsCollection;
use MovingImage\Bundle\MICommentsBundle\DTO\Comment as CommentDTO;
use DateTime;
use DateTimeZone;

class CommentsService
{
    /**
     * Default limit used when fetching comments.
     */
    const DEFAULT_LIMIT = 10;

    /**
     * Default offset used when fetching comments.
     */
    const DEFAULT_OFFSET = 0;

    /**
     * Default order property used when fetching comments.
     */
    const DEFAULT_ORDER_PROPERTY = 'dateCreated';

    /**
     * Default order direction used when fetching comments.
     */
    const DEFAULT_ORDER_DIRECTION = 'desc';

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * Affects the comment creation. If set to true, comments are automatically published.
     * By default, it is false.
     *
     * @var bool
     */
    private $autoPublish;

    /**
     * CommentsService constructor.
     *
     * @param EntityManager $entityManager
     * @param bool          $autoPublish
     */
    public function __construct(EntityManager $entityManager, bool $autoPublish = false)
    {
        $this->entityManager = $entityManager;
        $this->autoPublish = $autoPublish;
    }

    /**
     * Returns a CommentCollection containing all the comments matching the provided criteria.
     * If $entityId is provided, it will return only comments associated with that entity.
     * If $limit and $offset are provided, they will be used for pagination.
     *
     * @param null|string $entityId
     * @param int|null    $limit
     * @param int|null    $offset
     *
     * @return CommentsCollection
     */
    public function getComments(
        ?string $entityId = null,
        ?int $limit = null,
        ?int $offset = null
    ): CommentsCollection {
        if (is_null($limit)) {
            $limit = self::DEFAULT_LIMIT;
        }

        if (is_null($offset)) {
            $offset = self::DEFAULT_OFFSET;
        }

        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('status', Comment::STATUS_PUBLISHED))
            ->orderBy([self::DEFAULT_ORDER_PROPERTY => self::DEFAULT_ORDER_DIRECTION])
        ;

        if ($entityId) {
            $criteria->andWhere(Criteria::expr()->eq('entityId', $entityId));
        }

        $repository = $this->entityManager->getRepository(Comment::class);

        //even though it seems like we are fetching all results and then counting them,
        //doctrine is actually smart enough to convert this into a SELECT COUNT(*) query
        $totalCount = $repository->matching($criteria)->count();

        $criteria
            ->setFirstResult($offset)
            ->setMaxResults($limit)
        ;

        $comments = $repository->matching($criteria);
        $commentsDto = [];
        foreach ($comments as $comment) {
            $commentsDto[] = new CommentDTO($comment);
        }

        return new CommentsCollection($totalCount, $commentsDto, $limit, $offset);
    }

    /**
     * Creates a new Comment entity and stores it to DB.
     *
     * @param CommentDTO $commentDto
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function storeComment(CommentDTO $commentDto): void
    {
        $comment = new Comment();
        $comment->setDateCreated($this->getCurrentTime());
        $comment->setEntityId($commentDto->getEntity()->getId());
        $comment->setEntityTitle($commentDto->getEntity()->getTitle());
        $comment->setUserEmail($commentDto->getUser()->getEmail());
        $comment->setUserName($commentDto->getUser()->getName());
        $comment->setComment($commentDto->getComment());

        $this->entityManager->persist($comment);

        if ($this->autoPublish) {
            $this->publishComment($comment);
        }

        $this->entityManager->flush($comment);
    }

    /**
     * Publishes the provided Comment entity (sets the status to 'published' and sets the datePublished property).
     *
     * @param Comment $comment
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function publishComment(Comment $comment): void
    {
        if (Comment::STATUS_PUBLISHED === $comment->getStatus()) {
            return;
        }

        $comment->setStatus(Comment::STATUS_PUBLISHED);
        $comment->setDatePublished($this->getCurrentTime());
        $this->entityManager->flush($comment);
    }

    /**
     * Rejects the provided Comment entity (sets the status to 'rejected' and sets the dateRejected property).
     *
     * @param Comment $comment
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function rejectComment(Comment $comment): void
    {
        if (Comment::STATUS_REJECTED === $comment->getStatus()) {
            return;
        }

        $comment->setStatus(Comment::STATUS_REJECTED);
        $comment->setDateRejected($this->getCurrentTime());
        $this->entityManager->flush($comment);
    }

    /**
     * Gets a Comment entity by id.
     *
     * @param int $id
     *
     * @return Comment
     */
    public function getCommentById(int $id): Comment
    {
        return $this->entityManager->getRepository(Comment::class)->find($id);
    }

    /**
     * Returns a DateTime instance with current time and UTC time zone.
     *
     * @return DateTime
     */
    private function getCurrentTime(): DateTime
    {
        return new DateTime('now', new DateTimeZone('UTC'));
    }
}
