<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SonataPhpcrAdminIntegrationBundle\DependencyInjection\Factory;

use Symfony\Cmf\Bundle\MenuBundle\CmfMenuBundle;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@mayflower.de>
 */
class BlockAdminFactory implements AdminFactoryInterface
{
    use IsConfigEnabledTrait;

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
            ->arrayNode('extensions')
                ->addDefaultsIfNotSet()
                ->children()
                    ->arrayNode('block_cache')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('form_group')->defaultValue('form.group_metadata')->end()
                            ->scalarNode('form_tab')->defaultValue('form.tab_general')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
            ->scalarNode('basepath')->defaultNull()->end()
            ->scalarNode('menu_basepath')->defaultNull()->end()
            ->enumNode('enable_menu')
                ->values([true, false, 'auto'])
                ->defaultValue('auto')
            ->end()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $config, ContainerBuilder $container, XmlFileLoader $loader)
    {
        $container->setParameter('cmf_sonata_phpcr_admin_integration.block.basepath', $config['basepath']);
        $container->setParameter('cmf_sonata_phpcr_admin_integration.block.extension.block_cache.form_group', $config['extensions']['block_cache']['form_group']);
        $container->setParameter('cmf_sonata_phpcr_admin_integration.block.extension.block_cache.form_tab', $config['extensions']['block_cache']['form_tab']);

        $loader->load('block.xml');

        $message = 'CmfMenuBundle integration for the block admin was explicitely enabled, but the bundle is not available.';
        if (class_exists(CmfMenuBundle::class)) {
            $message .= ' (did you forget to register the bundle in the AppKernel?)';
        }
        if ($this->isConfigEnabledAuto($container, $config['enable_menu'], 'CmfMenuBundle', $message)) {
            $container->setParameter('cmf_sonata_phpcr_admin_integration.block.menu_basepath', $config['menu_basepath']);
            $loader->load('block-menu.xml');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasParameter('cmf_sonata_phpcr_admin_integration.block.basepath')) {
            return;
        }

        $basepath = $container->getParameter('cmf_sonata_phpcr_admin_integration.block.basepath');
        if (null === $basepath) {
            $container->setParameter(
                'cmf_sonata_phpcr_admin_integration.block.basepath',
                $container->getParameter('cmf_block.persistence.phpcr.block_basepath')
            );
        }

        if ($container->has('cmf_sonata_phpcr_admin_integration.block.menu_admin')) {
            // service is only loaded if menu indeed is enabled
            $menuBasepath = $container->getParameter('cmf_sonata_phpcr_admin_integration.block.menu_basepath');
            if (null === $menuBasepath) {
                $container->setParameter(
                    'cmf_sonata_phpcr_admin_integration.block.menu_basepath',
                    $container->getParameter('cmf_menu.persistence.menu_basepath')
                );
            }
        }
    }
}
