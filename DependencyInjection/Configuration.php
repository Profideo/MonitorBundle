<?php

namespace Profideo\MonitorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 *
 * @author Florent SEVESTRE <fsevestre@profideo.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('profideo_monitor');

        $rootNode
            ->append($this->getMonitorChecksNode())
        ;

        return $treeBuilder;
    }

    private function getMonitorChecksNode()
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root('checks');

        $node
            ->append($this->getMonitorChecksTableRowCountNode())
            ->end()
        ;

        return $node;
    }

    private function getMonitorChecksTableRowCountNode()
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root('table_row_count');

        $node
            ->info('Validate that database tables have a certain number of rows')
            ->useAttributeAsKey('name')
            ->treatNullLike(array())
            ->prototype('array')
                ->children()
                    ->arrayNode('tables')
                        ->example('["user", "role", "group"]')
                        ->isRequired()
                        ->requiresAtLeastOneElement()
                        ->prototype('scalar')->end()
                    ->end()

                    ->integerNode('min_rows')
                        ->isRequired()
                        ->min(0)
                    ->end()

                    ->integerNode('max_rows')
                        ->min(0)
                    ->end()
                ->end()
            ->end()
        ;

        return $node;
    }
}
