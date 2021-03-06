<?php

declare(strict_types=1);

namespace MovingImage\Bundle\MICommentsBundle\DependencyInjection;

use MovingImage\Bundle\MICommentsBundle\Admin\CommentAdmin;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('mi_comments');
        $rootNode
            ->children()
                ->scalarNode('auto_publish')->defaultValue(false)->end()
                ->scalarNode('css')->defaultValue(null)->end()
                ->scalarNode('display_max_length')->defaultValue(CommentAdmin::DEFAULT_COMMENT_DISPLAY_MAX_LENGTH)->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
