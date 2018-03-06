<?php

declare(strict_types=1);

namespace MovingImage\Bundle\MICommentsBundle\Controller;

use MovingImage\Bundle\MICommentsBundle\Service\CommentsService;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SonataCommentsController extends Controller
{
    /**
     * @param CommentsService $commentsService
     * @param int             $id
     *
     * @return RedirectResponse
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function publishAction(CommentsService $commentsService, int $id)
    {
        $comment = $commentsService->getCommentById($id);
        if ($comment) {
            $commentsService->publishComment($comment);
        }

        return new RedirectResponse($this->admin->generateUrl('list', [
            'filter' => $this->admin->getFilterParameters(),
        ]));
    }

    /**
     * @param CommentsService $commentsService
     * @param int             $id
     *
     * @return RedirectResponse
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function rejectAction(CommentsService $commentsService, int $id)
    {
        $comment = $commentsService->getCommentById($id);
        if ($comment) {
            $commentsService->rejectComment($comment);
        }

        return new RedirectResponse($this->admin->generateUrl('list', [
            'filter' => $this->admin->getFilterParameters(),
        ]));
    }
}
