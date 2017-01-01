<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SonataAdminIntegrationBundle\DependencyInjection\Factory;

use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * @author Wouter de Jong <wouter@wouterj.nl>
 */
class RoutingAdminFactory implements AdminFactoryInterface, CompilerPassInterface
{
    use PersistenceTrait;

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
    public function addConfiguration(NodeBuilder $persistenceBuilder, NodeBuilder $builder)
    {
        $this->addPersistenceNode('phpcr', $persistenceBuilder)
            ->scalarNode('basepath')->defaultNull()->end();
    }

    /**
     * {@inheritdoc}
     */
    public function create($persistence, array $config, ContainerBuilder $container, XmlFileLoader $loader)
    {
        $config['persistence'] = $this->useGlobalIfImplicit($persistence, $config['persistence']);

        if ($this->isConfigEnabled($container, $config['persistence']['phpcr'])) {
            $loader->load('routing-phpcr.xml');

            $container->setParameter('cmf_sonata_admin_integration.routing.phpcr.basepath', $config['persistence']['phpcr']['basepath']);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasParameter('cmf_sonata_admin_integration.routing.phpcr.basepath')) {
            return;
        }

        $basepath = $container->getParameter('cmf_sonata_admin_integration.routing.phpcr.basepath');
        if (null !== $basepath) {
            return;
        }

        $basepaths = $container->getParameter('cmf_routing.dynamic.persistence.phpcr.route_basepaths');
        $container->setParameter('cmf_sonata_admin_integration.routing.phpcr.basepath', reset($basepaths));
    }
}
