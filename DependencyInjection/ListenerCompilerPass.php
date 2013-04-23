<?php

namespace Amy\GoogleCheckoutBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Find all services tagged 'amy_google_checkout.notification_listener'
 * and add a call to the 'amy_google_checkout' server to register the listener.
 */
class ListenerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('amy_google_checkout')) {
            return;
        }

        $definition = $container->getDefinition('amy_google_checkout');
        $taggedServices = $container->findTaggedServiceIds('amy_google_checkout.notification_listener');
        foreach ($taggedServices as $id => $attributes) {
            $definition->addMethodCall('registerListener', array(new Reference($id)));
        }
    }
}
