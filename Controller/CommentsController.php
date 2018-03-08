<?php

declare(strict_types=1);

namespace MovingImage\Bundle\MICommentsBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use JMS\Serializer\Serializer;
use MovingImage\Bundle\MICommentsBundle\DTO\Comment;
use MovingImage\Bundle\MICommentsBundle\Service\CommentsService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CommentsController extends FOSRestController
{
    /**
     * GET /comments.
     *
     * Returns a list of comments matching the query criteria:
     *  - entityId = ID of the entity (eg video)
     *  - limit = number of results to return in the set
     *  - offset = number of results to skip in the set
     *
     * Only published comments are returned. The order is fixed: by createdDate, descending.
     *
     * @param CommentsService $commentsService
     * @param Request         $request
     *
     * @return Response
     */
    public function getAction(CommentsService $commentsService, Request $request): Response
    {
        $entityId = $request->query->get('entityId');
        $limit = $request->query->get('limit') ? (int) $request->query->get('limit') : null;
        $offset = $request->query->get('offset') ? (int) $request->query->get('offset') : null;

        $commentsCollection = $commentsService->getComments($entityId, $limit, $offset);
        $view = $this->view($commentsCollection);

        return $this->handleView($view);
    }

    /**
     * POST /comments.
     *
     * Creates and stores new comment provided in the request body.
     * Example request:
     *  {
     *      "user": {
     *          "email": "pavle.predic@movingimage.com",
     *          "name": "Pavle Predic"
     *      },
     *      "entity": {
     *          "id": "DF3osABraqfGPaSGtty8vt",
     *          "title": "Recruiting Teaser Karo & Matthias"
     *      },
     *      "comment": "What an awesome video! Great job!"
     *  }
     *
     * If the bundle configuration option "auto_publish" is set to true
     * the comment will be automatically published. Otherwise you will
     * need to publish it manually from the admin section.
     *
     * @param CommentsService    $commentsService
     * @param Request            $request
     * @param Serializer         $serializer
     * @param ValidatorInterface $validator
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function postAction(
        CommentsService $commentsService,
        Request $request,
        Serializer $serializer,
        ValidatorInterface $validator
    ): Response {
        $postData = $request->getContent();
        if (!$postData || !json_decode($postData)) {
            throw new BadRequestHttpException();
        }

        $comment = $serializer->deserialize($postData, Comment::class, 'json');

        $errors = $validator->validate($comment);
        if ($errors->count()) {
            throw new BadRequestHttpException();
        }

        $commentsService->storeComment($comment);

        return new Response('', Response::HTTP_CREATED);
    }
}
