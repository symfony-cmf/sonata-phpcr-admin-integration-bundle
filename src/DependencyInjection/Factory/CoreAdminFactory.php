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
 * @author Maximilian Berghoff <maximilian.berghoff@gmx.de>
 */
class CoreAdminFactory implements AdminFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getKey()
    {
        return 'core';
    }

    /**
     * {@inheritdoc}
     */
    public function addConfiguration(NodeBuilder $builder)
    {
        $builder
            ->scalarNode('form_group')->defaultValue('form.group_core')->end()
            ->arrayNode('extensions')
                ->addDefaultsIfNotSet()
                ->children()
                    ->arrayNode('publishable')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('form_group')->defaultValue('form.group_publish_workflow')->end()
                        ->end()
                    ->end()
                    ->arrayNode('publish_time')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('form_group')->defaultValue('form.group_publish_workflow')->end()
                        ->end()
                    ->end()
                    ->arrayNode('translatable')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('form_group')->defaultValue('form.group_general')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $config, ContainerBuilder $container, XmlFileLoader $loader)
    {
        $loader->load('core.xml');

        $container->setParameter('cmf_sonata_admin_integration.core.form_group', $config['form_group']);

        if (!isset($config['extensions'])) {
            return;
        }

        foreach ($config['extensions'] as $extension => $values) {
            $container->setParameter(
                'cmf_sonata_admin_integration.core.'.$extension.'.form_group',
                $values['form_group']
            );
        }
    }
}
