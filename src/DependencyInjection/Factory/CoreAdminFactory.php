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
    use PersistenceTrait;

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
    public function addConfiguration(NodeBuilder $persistenceConfig, NodeBuilder $builder)
    {
        $this->addPersistenceNode('phpcr', $persistenceConfig);

        $builder
            ->arrayNode('extensions')
                ->addDefaultsIfNotSet()
                ->children()
                    ->arrayNode('publishable')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('form_group')->defaultValue('form.group_publish_workflow')->end()
                            ->scalarNode('form_tab')->defaultValue('form.tab_publish')->end()
                        ->end()
                    ->end()
                    ->arrayNode('publish_time')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('form_group')->defaultValue('form.group_publish_workflow')->end()
                            ->scalarNode('form_tab')->defaultValue('form.tab_publish')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    /**
     * {@inheritdoc}
     */
    public function create($persistence, array $config, ContainerBuilder $container, XmlFileLoader $loader)
    {
        $config['persistence'] = $this->useGlobalIfImplicit($persistence, $config['persistence']);

        if ($this->isConfigEnabled($container, $config['persistence']['phpcr'])) {
            $this->loadPhpcr($config, $container, $loader);
        }
    }

    private function loadPhpcr(array $config, ContainerBuilder $container, XmlFileLoader $loader)
    {
        $loader->load('core-phpcr.xml');

        foreach ($config['extensions'] as $extension => $values) {
            $container->setParameter('cmf_sonata_admin_integration.core.'.$extension.'.form_group', $values['form_group']);
            $container->setParameter('cmf_sonata_admin_integration.core.'.$extension.'.form_tab', $values['form_tab']);
        }
    }
}
