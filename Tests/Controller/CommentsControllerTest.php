<?php

namespace Tests\Controller;

use FOS\RestBundle\View\ViewHandler;
use MovingImage\Bundle\MICommentsBundle\Controller\CommentsController;
use MovingImage\Bundle\MICommentsBundle\DTO\Comment;
use MovingImage\Bundle\MICommentsBundle\Entity\Comment as CommentEntity;
use MovingImage\Bundle\MICommentsBundle\Service\CommentsService;
use Symfony\Component\HttpFoundation\Request;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;
use JMS\Serializer\Serializer;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CommentsControllerTest extends TestCase
{
    /**
     * @covers \CommentsController::getAction()
     * @dataProvider dataProviderForTestGetAction
     *
     * @param array  $queryParams
     * @param string $expectedEntityId
     * @param string $expectedLimit
     * @param string $expectedOffset
     *
     * @throws \ReflectionException
     */
    public function testGetAction(
        array $queryParams,
        ?string $expectedEntityId = null,
        ?string $expectedLimit = null,
        ?string $expectedOffset = null
    ) {
        $viewHandler = $this->createMock(ViewHandler::class);
        $viewHandler->method('handle')->willReturn(new Response());

        $controller = new CommentsController();
        $controller->setViewHandler($viewHandler);

        $commentsService = $this->prophesize(CommentsService::class);
        $request = new Request($queryParams);

        $commentsService
            ->getComments($expectedEntityId, $expectedLimit, $expectedOffset)
            ->shouldBeCalled()
        ;

        $controller->getAction($commentsService->reveal(), $request);
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetAction()
    {
        return [
            [[], null, null, null],
            [['entityId' => '1'], 1, null, null],
            [['entityId' => '1', 'limit' => 10], 1, 10, null],
            [['entityId' => '1', 'limit' => 10, 'offset' => 20], 1, 10, 20],
        ];
    }

    /**
     * @covers \CommentsController::postAction()
     *
     * @throws \ReflectionException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function testPostAction()
    {
        $comment = new Comment((new CommentEntity())->setEntityId('123'));

        $serializer = $this->createMock(Serializer::class);
        $serializer->method('deserialize')->willReturn($comment);

        $validator = $this->createMock(ValidatorInterface::class);
        $validator->method('validate')->willReturn(new ConstraintViolationList());

        $container = $this->createMock(ContainerInterface::class);
        $container->method('get')->willReturnCallback(function ($service) use ($serializer, $validator) {
            switch ($service) {
                case 'jms_serializer': return $serializer;
                case 'validator': return $validator;
            }
        });

        $controller = new CommentsController();
        $controller->setContainer($container);

        $commentsService = $this->prophesize(CommentsService::class);
        $request = new Request([], [], [], [], [], [], '{}');

        $commentsService
            ->storeComment($comment)
            ->shouldBeCalled()
        ;

        $response = $controller->postAction($commentsService->reveal(), $request);
        $this->assertSame(201, $response->getStatusCode());
    }
}
