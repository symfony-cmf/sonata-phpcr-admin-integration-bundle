<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SonataAdminIntegrationBundle\DependencyInjection\Factory;

use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * @author Wouter de Jong <wouter@wouterj.nl>
 */
class MenuAdminFactory implements AdminFactoryInterface
{
    use PersistenceTrait;

    /**
     * {@inheritdoc}
     */
    public function getKey()
    {
        return 'menu';
    }

    /**
     * {@inheritdoc}
     */
    public function addConfiguration(NodeBuilder $persistenceBuilder, NodeBuilder $builder)
    {
        $this->addPersistenceNode('phpcr', $persistenceBuilder);

        $builder
            ->booleanNode('recursive_breadcrumbs')->defaultTrue()->end()
            ->arrayNode('extensions')
                ->addDefaultsIfNotSet()
                ->children()
                    ->arrayNode('menu_options')
                        ->addDefaultsIfNotSet()
                        ->canBeDisabled()
                        ->children()
                            ->booleanNode('advanced')->defaultValue(false)->end()
                            ->scalarNode('form_group')->defaultValue('form.group_menu_options')->end()
                            ->scalarNode('form_tab')->defaultValue('form.tab_general')->end()
                        ->end()
                    ->end()
                    ->arrayNode('menu_node_referrers')
                        ->canBeDisabled()
                        ->children()
                            ->scalarNode('form_group')->defaultValue('form.group_menus')->end()
                            ->scalarNode('form_tab')->defaultValue('form.tab_menu')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function create($persistence, array $config, ContainerBuilder $container, XmlFileLoader $loader)
    {
        $config['persistence'] = $this->useGlobalIfImplicit($persistence, $config['persistence']);

        $container->setParameter('cmf_sonata_admin_integration.menu.recursive_breadcrumbs', 'recursive_breadcrumbs');

        if ($this->isConfigEnabled($container, $config['persistence']['phpcr'])) {
            $loader->load('menu-phpcr.xml');

            $this->loadExtensions($config['extensions'], $container, $loader);
        }
    }

    private function loadExtensions(array $config, ContainerBuilder $container, XmlFileLoader $loader)
    {
        $bundles = $container->getParameter('kernel.bundles');

        foreach ($config as $name => $extensionConfig) {
            switch ($name) {
                case 'menu_options':
                    if ($this->isConfigEnabled($container, $extensionConfig)
                        && $extensionConfig['advanced']
                        && !isset($bundles['BurgovKeyValueFormBundle'])
                    ) {
                        throw new InvalidConfigurationException('To use advanced menu options, you need the burgov/key-value-form-bundle in your project.');
                    }

                    $container->setParameter('cmf_sonata_admin_integration.menu.extension.menu_options.advanced', $config['menu_options']['advanced']);

                    // no break is intended to allow disabling the menu_options extension
                default:
                    if (!$this->isConfigEnabled($container, $extensionConfig)) {
                        $container->removeDefinition('cmf_sonata_admin_integration.menu.extension.'.$name);
                    }

                    $container->setParameter('cmf_sonata_admin_integration.menu.extension.'.$name.'.form_group', $extensionConfig['form_group']);
                    $container->setParameter('cmf_sonata_admin_integration.menu.extension.'.$name.'.form_tab', $extensionConfig['form_tab']);
            }
        }
    }
}
