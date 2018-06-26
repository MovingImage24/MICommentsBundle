<?php

declare(strict_types=1);

namespace MovingImage\Bundle\MICommentsBundle\Admin;

use MovingImage\Bundle\MICommentsBundle\Entity\Comment;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Form\FormMapper;

class CommentAdmin extends AbstractAdmin
{
    /**
     * Default value for commentsDisplayMaxLength.
     */
    const DEFAULT_COMMENT_DISPLAY_MAX_LENGTH = 20;

    /**
     * Default datagrid values.
     * Assures that comments are sorted by dateCreated descending by default.
     *
     * @var array
     */
    protected $datagridValues = [
        '_page' => 1,
        '_sort_order' => 'DESC',
        '_sort_by' => 'dateCreated',
    ];

    /**
     * @var string
     */
    protected $translationDomain = 'MICommentsBundle';

    /**
     * Max length of the comment in the list view.
     *
     * @var int
     */
    private $commentDisplayMaxLength = self::DEFAULT_COMMENT_DISPLAY_MAX_LENGTH;

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('dateCreated', 'doctrine_orm_datetime_range');
        $datagridMapper->add('userName');
        $datagridMapper->add('userEmail');
        $datagridMapper->add('entityId');
        $datagridMapper->add('entityTitle');
        $datagridMapper->add('comment');
        $datagridMapper->add('administratorReply');
        $datagridMapper->add('status', 'doctrine_orm_choice', [], 'choice', [
            'choices' => [
                Comment::STATUS_PUBLISHED => Comment::STATUS_PUBLISHED,
                Comment::STATUS_REJECTED => Comment::STATUS_REJECTED,
                Comment::STATUS_PENDING => Comment::STATUS_PENDING,
            ],
            'translation_domain' => $this->translationDomain,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('dateCreated');
        $listMapper->addIdentifier('userName');
        $listMapper->addIdentifier('userEmail');
        $listMapper->addIdentifier('entityId');
        $listMapper->addIdentifier('entityTitle');
        $listMapper->addIdentifier('comment', null, [
            'template' => '@MIComments/Sonata/list_truncated.html.twig',
            'length' => $this->getCommentDisplayMaxLength(),
        ]);
        $listMapper->addIdentifier('administratorReply', 'boolean');
        $listMapper->addIdentifier('status', null, [
            'template' => '@MIComments/Sonata/list_status.html.twig',
        ]);
        $listMapper->add('_action', null, [
            'actions' => [
                'publish' => [
                    'template' => '@MIComments/Sonata/list_action_publish.html.twig',
                ],
                'reject' => [
                    'template' => '@MIComments/Sonata/list_action_reject.html.twig',
                ],
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('userName', 'text', ['disabled' => true]);
        $formMapper->add('userEmail', 'text', ['disabled' => true]);
        $formMapper->add('entityId', 'text', ['disabled' => true]);
        $formMapper->add('entityTitle', 'text', ['disabled' => true]);
        //$formMapper->add('dateCreated', 'text', ['disabled'  => true]);
        $formMapper->add('status', 'choice', [
            'choices' => [
                Comment::STATUS_PUBLISHED => Comment::STATUS_PUBLISHED,
                Comment::STATUS_REJECTED => Comment::STATUS_REJECTED,
                Comment::STATUS_PENDING => Comment::STATUS_PENDING
            ]
        ]);
        $formMapper->add('comment', 'textarea', ['disabled' => true]);
        $formMapper->add('administratorReply', 'textarea', ['required' => false]);

    }

    /**
     * {@inheritdoc}
     */
    public function toString($comment)
    {
        if (!($comment instanceof Comment)) {
            return parent::toString($comment);
        }

        if ($comment->getId()) {
            return 'Comment#'.$comment->getId();
        } else {
            return 'New Comment';
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('export');
        $collection->remove('create');
        $collection->remove('show');
        $collection->add('publish', $this->getRouterIdParameter().'/publish');
        $collection->add('reject', $this->getRouterIdParameter().'/reject');
    }

    /**
     * @return int
     */
    public function getCommentDisplayMaxLength(): int
    {
        return $this->commentDisplayMaxLength;
    }

    /**
     * @param int $commentDisplayMaxLength
     */
    public function setCommentDisplayMaxLength(int $commentDisplayMaxLength): void
    {
        $this->commentDisplayMaxLength = $commentDisplayMaxLength;
    }
}
