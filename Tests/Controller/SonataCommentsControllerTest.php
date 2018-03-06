<?php

declare(strict_types=1);

namespace Tests\Controller;

use MovingImage\Bundle\MICommentsBundle\Admin\CommentAdmin;
use MovingImage\Bundle\MICommentsBundle\Controller\SonataCommentsController;
use MovingImage\Bundle\MICommentsBundle\Entity\Comment;
use MovingImage\Bundle\MICommentsBundle\Service\CommentsService;
use PHPUnit\Framework\TestCase;
use Sonata\AdminBundle\Admin\Pool;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class SonataCommentsControllerTest extends TestCase
{
    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \ReflectionException
     */
    public function testPublishAction()
    {
        $id = 123;
        $comment = new Comment();
        $commentsService = $this->prophesize(CommentsService::class);
        $commentsService->getCommentById($id)->shouldBeCalled()->willReturn($comment);
        $commentsService->publishComment($comment)->shouldBeCalled();

        $this->createController()->publishAction($commentsService->reveal(), $id);
    }

    /**
     * @covers \SonataCommentsController::rejectAction()
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \ReflectionException
     */
    public function testRejectAction()
    {
        $id = 123;
        $comment = new Comment();
        $commentsService = $this->prophesize(CommentsService::class);
        $commentsService->getCommentById($id)->shouldBeCalled()->willReturn($comment);
        $commentsService->rejectComment($comment)->shouldBeCalled();

        $this->createController()->rejectAction($commentsService->reveal(), $id);
    }

    /**
     * @return SonataCommentsController
     *
     * @throws \ReflectionException
     */
    private function createController()
    {
        $request = new Request([], [], ['_sonata_admin' => CommentAdmin::class]);
        $requestStack = new RequestStack();
        $requestStack->push($request);

        $admin = $this->createMock(CommentAdmin::class);
        $admin->method('generateUrl')->willReturn('http://example.org');
        $admin->method('getFilterParameters')->willReturn([]);
        $container = $this->createMock(ContainerInterface::class);
        $pool = $this->createMock(Pool::class);

        $pool->method('getAdminByAdminCode')->with(CommentAdmin::class)->willReturn($admin);

        $container->method('get')->willReturnCallback(function ($service) use ($requestStack, $pool) {
            switch ($service) {
                case 'request_stack':
                    return $requestStack;
                case 'sonata.admin.pool':
                    return $pool;
            }
        });

        $controller = new SonataCommentsController();
        $controller->setContainer($container);

        return $controller;
    }
}
