<?php

namespace Amy\GoogleCheckoutBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AmyGoogleCheckoutBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new DependencyInjection\ListenerCompilerPass());
    }
}
