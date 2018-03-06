<?php

declare(strict_types=1);

namespace Tests\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use MovingImage\Bundle\MICommentsBundle\DTO\Comment;
use MovingImage\Bundle\MICommentsBundle\Entity\Comment as CommentEntity;
use MovingImage\Bundle\MICommentsBundle\Repository\CommentRepository;
use MovingImage\Bundle\MICommentsBundle\Service\CommentsService;
use PHPUnit\Framework\TestCase;
use Doctrine\ORM\EntityManager;

class CommentsServiceTest extends TestCase
{
    /**
     * @covers \CommentsService::getComments()
     *
     * @throws \ReflectionException
     */
    public function testGetComments()
    {
        $comments = [
            (new CommentEntity())->setComment('first comment'),
            (new CommentEntity())->setComment('second comment'),
        ];

        $repository = $this->createMock(EntityRepository::class);
        $repository->method('matching')->willReturn(new ArrayCollection($comments));
        $em = $this->createMock(EntityManager::class);
        $em->method('getRepository')->willReturn($repository);

        $service = new CommentsService($em);
        $collection = $service->getComments();

        $this->assertSame(2, $collection->getTotalCount());
        $commentDTOs = $collection->getComments();
        $this->assertSame('first comment', $commentDTOs[0]->getComment());
        $this->assertSame('second comment', $commentDTOs[1]->getComment());
    }

    /**
     * @covers \CommentsService::storeComment()
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \ReflectionException
     */
    public function testStoreComment()
    {
        $commentEntity = (new CommentEntity())
            ->setEntityId('123')
            ->setEntityTitle('title')
            ->setUserName('user')
            ->setUserEmail('email')
            ->setComment('first comment')
        ;

        $phpUnit = $this;
        $em = $this->createMock(EntityManager::class);
        $em->method('persist')->willReturnCallback(function ($persistedEntity) use ($commentEntity, $phpUnit) {
            /* @var CommentEntity $persistedEntity */
            $phpUnit->assertInstanceOf(CommentEntity::class, $persistedEntity);
            $phpUnit->assertSame($commentEntity->getEntityId(), $persistedEntity->getEntityId());
            $phpUnit->assertSame($commentEntity->getEntityTitle(), $persistedEntity->getEntityTitle());
            $phpUnit->assertSame($commentEntity->getUserName(), $persistedEntity->getUserName());
            $phpUnit->assertSame($commentEntity->getUserEmail(), $persistedEntity->getUserEmail());
            $phpUnit->assertSame($commentEntity->getComment(), $persistedEntity->getComment());
        });

        $service = new CommentsService($em);

        $commentDTO = new Comment($commentEntity);
        $service->storeComment($commentDTO);
    }

    /**
     * @covers \CommentsService::publishComment()
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function testPublishComment()
    {
        $comment = new CommentEntity();

        $em = $this->prophesize(EntityManager::class);
        $em->flush($comment)->shouldBeCalled();

        $service = new CommentsService($em->reveal());
        $service->publishComment($comment);

        $this->assertSame(CommentEntity::STATUS_PUBLISHED, $comment->getStatus());
        $this->assertNotEmpty($comment->getDatePublished());
    }

    /**
     * @covers \CommentsService::rejectComment()
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function testRejectComment()
    {
        $comment = new CommentEntity();

        $em = $this->prophesize(EntityManager::class);
        $em->flush($comment)->shouldBeCalled();

        $service = new CommentsService($em->reveal());
        $service->rejectComment($comment);

        $this->assertSame(CommentEntity::STATUS_REJECTED, $comment->getStatus());
        $this->assertNotEmpty($comment->getDateRejected());
    }

    /**
     * @covers \CommentsService::rejectComment()
     *
     * @throws \ReflectionException
     */
    public function testGetCommentById()
    {
        $id = 123;
        $comment = new CommentEntity();
        $repository = $this->prophesize(CommentRepository::class);
        $repository->find($id)->shouldBeCalled()->willReturn($comment);
        $em = $this->createMock(EntityManager::class);
        $em->method('getRepository')->willReturn($repository->reveal());

        $service = new CommentsService($em);
        $returnedComment = $service->getCommentById($id);
        $this->assertSame($comment, $returnedComment);
    }
}
