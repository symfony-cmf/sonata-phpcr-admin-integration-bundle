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
            ->end()
            ->arrayNode('admin_classes')
                ->children()
                    ->scalarNode('string_document_class')
                        ->defaultValue('Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\StringBlock')
                    ->end()
                    ->scalarNode('simple_document_class')
                        ->defaultValue('Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\SimpleBlock')
                    ->end()
                    ->scalarNode('container_document_class')
                        ->defaultValue('Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\ContainerBlock')
                    ->end()
                    ->scalarNode('reference_document_class')
                        ->defaultValue('Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\ReferenceBlock')
                    ->end()
                    ->scalarNode('action_document_class')
                        ->defaultValue('Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\ActionBlock')
                    ->end()
                    ->scalarNode('slideshow_document_class')
                        ->defaultValue('Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\SlideshowBlock')
                    ->end()
                    ->scalarNode('imagine_document_class')
                        ->defaultValue('Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\ImagineBlock')
                    ->end()
                    ->scalarNode('menu_document_class')
                        ->defaultValue('Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\MenuBlock')
                    ->end()
                    ->scalarNode('string_admin_class')
                        ->defaultValue('Symfony\Cmf\Bundle\SonataAdminIntegrationBundle\Admin\Block\StringBlockAdmin')
                    ->end()
                    ->scalarNode('simple_admin_class')
                        ->defaultValue('Symfony\Cmf\Bundle\SonataAdminIntegrationBundle\Admin\Block\SimpleBlockAdmin')
                    ->end()
                    ->scalarNode('container_admin_class')
                        ->defaultValue('Symfony\Cmf\Bundle\SonataAdminIntegrationBundle\Admin\Block\ContainerBlockAdmin')
                    ->end()
                    ->scalarNode('reference_admin_class')
                        ->defaultValue('Symfony\Cmf\Bundle\SonataAdminIntegrationBundle\Admin\Block\ReferenceBlockAdmin')
                    ->end()
                    ->scalarNode('action_admin_class')
                        ->defaultValue('Symfony\Cmf\Bundle\SonataAdminIntegrationBundle\Admin\Block\ActionBlockAdmin')
                    ->end()
                    ->scalarNode('slideshow_admin_class')
                        ->defaultValue('Symfony\Cmf\Bundle\SonataAdminIntegrationBundle\Admin\Block\Imagine\SlideshowBlockAdmin')
                    ->end()
                    ->scalarNode('imagine_admin_class')
                        ->defaultValue('Symfony\Cmf\Bundle\SonataAdminIntegrationBundle\Admin\Block\Imagine\ImagineBlockAdmin')
                    ->end()
                    ->scalarNode('menu_admin_class')
                        ->defaultValue('Symfony\Cmf\Bundle\SonataAdminIntegrationBundle\Admin\Block\MenuBlockAdmin')
                    ->end()
                ->end()
            ->end();
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $config, ContainerBuilder $container, XmlFileLoader $loader)
    {
        $container->setParameter('cmf_sonata_admin_integration.block.phpcr.basepath', $config['basepath']);
        $container->setParameter('cmf_sonata_admin_integration.block.phpcr.menu_basepath', $config['menu_basepath']);
        $keys = [
            'string_document_class' => 'string_document.class',
            'simple_document_class' => 'simple_document.class',
            'container_document_class' => 'container_document.class',
            'reference_document_class' => 'reference_document.class',
            'menu_document_class' => 'menu_document.class',
            'action_document_class' => 'action_document.class',
            'imagine_document_class' => 'imagine_document.class',
            'slideshow_document_class' => 'slideshow_document.class',
            'string_admin_class' => 'string_admin.class',
            'simple_admin_class' => 'simple_admin.class',
            'container_admin_class' => 'container_admin.class',
            'reference_admin_class' => 'reference_admin.class',
            'menu_admin_class' => 'menu_admin.class',
            'action_admin_class' => 'action_admin.class',
            'imagine_admin_class' => 'imagine_admin.class',
            'slideshow_admin_class' => 'slideshow_admin.class',
        ];
        $adminClasses = $config['admin_classes'];
        foreach ($keys as $sourceKey => $targetKey) {
            $container->setParameter('cmf_sonata_admin_integration.block.'.$targetKey, $adminClasses[$sourceKey]);
        }

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
