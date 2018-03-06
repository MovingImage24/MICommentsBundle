<?php

declare(strict_types=1);

namespace MovingImage\Bundle\MICommentsBundle\Admin;

use MovingImage\Bundle\MICommentsBundle\Entity\Comment;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;

class CommentAdmin extends AbstractAdmin
{
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
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('userName');
        $datagridMapper->add('userEmail');
        $datagridMapper->add('entityId');
        $datagridMapper->add('entityTitle');
        $datagridMapper->add('comment');
        $datagridMapper->add('dateCreated', 'doctrine_orm_datetime_range');
        $datagridMapper->add('status', 'doctrine_orm_choice', [], 'choice', [
            'choices' => [
                Comment::STATUS_PUBLISHED => Comment::STATUS_PUBLISHED,
                Comment::STATUS_REJECTED => Comment::STATUS_REJECTED,
                Comment::STATUS_PENDING => Comment::STATUS_PENDING,
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('userName');
        $listMapper->addIdentifier('userEmail');
        $listMapper->addIdentifier('entityId');
        $listMapper->addIdentifier('entityTitle');
        $listMapper->addIdentifier('comment', null, [
            'template' => '@MIComments/Sonata/list_truncated.html.twig',
        ]);
        $listMapper->addIdentifier('dateCreated');
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
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper->add('userName');
        $showMapper->add('userEmail');
        $showMapper->add('entityId');
        $showMapper->add('entityTitle');
        $showMapper->add('comment');
        $showMapper->add('dateCreated');
        $showMapper->add('status');
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
        $collection->remove('edit');
        $collection->remove('create');
        $collection->remove('delete');
        $collection->add('publish', $this->getRouterIdParameter().'/publish');
        $collection->add('reject', $this->getRouterIdParameter().'/reject');
    }
}
