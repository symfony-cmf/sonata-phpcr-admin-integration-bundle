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

use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * @author Wouter de Jong <wouter@wouterj.nl>
 */
class RoutingAdminFactory implements AdminFactoryInterface, CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function getKey()
    {
        return 'routing';
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
                    ->arrayNode('referrers')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('form_group')->defaultValue('form.group_routes')->end()
                            ->scalarNode('form_tab')->defaultValue('form.tab_routes')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
            ->scalarNode('basepath')->defaultNull()->end()
            ->scalarNode('content_basepath')->defaultNull()->end();
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $config, ContainerBuilder $container, XmlFileLoader $loader)
    {
        $loader->load('routing.xml');

        $container->setParameter('cmf_sonata_phpcr_admin_integration.routing.basepath', $config['basepath']);
        $container->setParameter('cmf_sonata_phpcr_admin_integration.routing.content_basepath', $config['content_basepath']);
        $container->setParameter(
            'cmf_sonata_phpcr_admin_integration.routing.extension.referrers.from_group',
            $config['extensions']['referrers']['form_group']
        );
        $container->setParameter(
            'cmf_sonata_phpcr_admin_integration.routing.extension.referrers.from_tab',
            $config['extensions']['referrers']['form_tab']
        );
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasParameter('cmf_sonata_phpcr_admin_integration.routing.basepath')) {
            return;
        }

        $basepath = $container->getParameter('cmf_sonata_phpcr_admin_integration.routing.basepath');
        if (null === $basepath) {
            $basepaths = $container->getParameter('cmf_routing.dynamic.persistence.phpcr.route_basepaths');
            $container->setParameter('cmf_sonata_phpcr_admin_integration.routing.basepath', reset($basepaths));
        }

        $contentBasepath = $container->getParameter('cmf_sonata_phpcr_admin_integration.routing.content_basepath');
        if (null === $contentBasepath) {
            $contentBasepath = $container->hasParameter('cmf_content.persistence.phpcr.content_basepath')
                ? $container->getParameter('cmf_content.persistence.phpcr.content_basepath')
                : '/';
            $container->setParameter('cmf_sonata_phpcr_admin_integration.routing.content_basepath', $contentBasepath);
        }
    }
}
