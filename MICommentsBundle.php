<?php

declare(strict_types=1);

namespace MovingImage\Bundle\MICommentsBundle;

use MovingImage\Bundle\MICommentsBundle\DependencyInjection\Compiler\TwigGlobalsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class MICommentsBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new TwigGlobalsPass());
    }
}
