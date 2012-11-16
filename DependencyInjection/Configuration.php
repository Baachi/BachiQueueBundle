<?php

namespace Bachi\QueueBundle\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * @author Markus Bachmann <markus.bachmann@bachi.biz>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder();
        $root    = $builder->root('bachi_queue');

        $root
            ->children()
            ->arrayNode('queues')
                ->prototype('array')
                    ->children()
                        ->scalarNode('name')->isRequired()->end()
                        ->scalarNode('storage')->isRequired()->end()
                    ->end()
                ->end()
            ->end()
            ->arrayNode('storages')
                ->prototype('array')
                    ->children()
                        ->scalarNode('type')->isRequired()->end()
                        ->scalarNode('id')->end()
                        ->scalarNode('path')->end()
                        ->scalarNode('host')->end()
                        ->scalarNode('port')->end()
                    ->end()
                ->end()
            ->end();

        return $builder;
    }
}
