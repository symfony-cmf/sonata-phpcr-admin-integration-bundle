<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SonataPhpcrAdminIntegrationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Cmf\Bundle\SonataPhpcrAdminIntegrationBundle\DependencyInjection\Factory\AdminFactoryInterface;

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@mayflower.de>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @var AdminFactoryInterface[]
     */
    private $factories;

    public function __construct(array $factories = [])
    {
        $this->factories = $factories;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $root = $treeBuilder->root('cmf_sonata_phpcr_admin_integration');

        $this->addBundlesSection($root);

        return $treeBuilder;
    }

    private function addBundlesSection(ArrayNodeDefinition $root)
    {
        $bundles = $root->children()->arrayNode('bundles')->isRequired()->children();

        foreach ($this->factories as $factory) {
            $config = $bundles
                ->arrayNode($factory->getKey())
                    ->addDefaultsIfNotSet()
                    ->canBeEnabled()
                    ->children();

            $factory->addConfiguration($config);
        }
    }
}
