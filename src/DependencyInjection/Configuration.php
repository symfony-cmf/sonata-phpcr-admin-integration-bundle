<?php

namespace Symfony\Cmf\Bundle\SonataAdminBundle\DependencyInjection;

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
        $treeBuilder->root('cmf_sonata_admin')
            ->children()
                ->arrayNode('bundles')
                    ->children()
                        ->append($this->simpleBundle('seo'))
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }

    /**
     * Creates a simple bundle activation with a form group name configuration.
     *
     * @param $name
     * @return mixed
     */
    private function simpleBundle($name)
    {
        $builder = new TreeBuilder();
        $node = $builder->root($name);

        return $node
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
                ->scalarNode('form_group')->defaultValue('form.group_'.$name)->end()
            ->end()
        ;
    }
}
