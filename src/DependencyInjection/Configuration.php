<?php

namespace Symfony\Cmf\Bundle\SonataAdminIntegrationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@mayflower.de>
 */
class Configuration implements ConfigurationInterface
{

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('cmf_sonata_admin_integration')
            ->fixXmlConfig('bundle')
            ->children()
                ->arrayNode('dynamic')
                    ->children()
                        ->enumNode('persistence')
                            ->cannotBeEmpty()
                            ->values(['phpcr', 'orm'])
                            ->defaultValue('phpcr')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('bundles')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->addDefaultsIfNotSet()
                        ->beforeNormalization()
                            ->ifTrue(function ($v) { return is_scalar($v); })
                            ->then(function ($v) {
                                return ['enabled' => $v];
                            })
                        ->end()
                        ->children()
                            ->enumNode('enabled')
                                ->values([true, false, 'auto'])
                                ->defaultValue('auto')
                            ->end()
                            ->scalarNode('form_group')->defaultValue('form.group')->end()
                            ->scalarNode('admin_basepath')->defaultNull()->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
