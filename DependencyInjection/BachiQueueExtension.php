<?php

namespace Bachi\QueueBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Config\FileLocator;

/**
 * @author Markus Bachmann <markus.bachmann@bachi.biz>
 */
class BachiQueueExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $config);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        foreach ($config['storages'] as $storage) {
            $type = $storage['type'];
            unset($storage['type']);

            foreach ($storage as $key => $value) {
                $container->setParameter(
                    'bachi_queue.storage.'.$type.'.options.'.$key,
                    $value
                );
            }

            $loader->load('storages/'.$type.'.xml');
        }

        $qm = $container->getDefinition('bachi_queue.queue_manager');

        foreach ($config['queues'] as $queue) {
            $definition = new Definition('%bachi_queue.queue.class%');
            $definition->setArguments(array(
                new Reference('service_container'),
                new Reference('bachi_queue.storage.'.$queue['storage']),
                $queue['name']
            ));

            $qm->addMethodCall('register', array($definition));
        }
    }
}
