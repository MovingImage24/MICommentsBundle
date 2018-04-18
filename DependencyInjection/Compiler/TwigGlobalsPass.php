<?php

namespace MovingImage\Bundle\MICommentsBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TwigGlobalsPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $twig = $container->getDefinition('twig');
        $css = $container->getParameter('mi_comments.css');
        $twig->addMethodCall('addGlobal', ['mi_comments_css', $css]);
    }
}
