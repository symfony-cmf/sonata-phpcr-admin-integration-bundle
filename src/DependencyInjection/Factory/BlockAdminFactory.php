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
 * @author Maximilian Berghoff <Maximilian.Berghoff@mayflower.de>
 */
class BlockAdminFactory implements AdminFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getKey()
    {
        return 'block';
    }

    /**
     * {@inheritdoc}
     */
    public function addConfiguration(NodeBuilder $builder)
    {
        $builder
            ->scalarNode('basepath')->defaultNull()->end()
            ->scalarNode('menu_basepath')->defaultNull()->end()
            ->enumNode('use_imagine')
                ->values([true, false, 'auto'])
                ->defaultValue('auto')
            ->end();
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $config, ContainerBuilder $container, XmlFileLoader $loader)
    {
        $container->setParameter('cmf_sonata_admin_integration.block.persistence.phpcr.basepath', $config['basepath']);
        $container->setParameter(
            'cmf_sonata_admin_integration.block.persistence.phpcr.menu_basepath',
            $config['menu_basepath']
        );

        $bundles = $container->getParameter('kernel.bundles');
        $loader->load('block.xml');

        if ($config['use_imagine']) {
            $loader->load('block-imagine.xml');
        }

        if (isset($bundles['CmfMenuBundle'])) {
            $loader->load('block-menu.xml');
        }
    }
}
