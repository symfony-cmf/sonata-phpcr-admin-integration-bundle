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
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * @author Wouter de Jong <wouter@wouterj.nl>
 */
class ContentAdminFactory implements AdminFactoryInterface
{
    use PersistenceTrait;

    /**
     * {@inheritdoc}
     */
    public function getKey()
    {
        return 'content';
    }

    /**
     * {@inheritdoc}
     */
    public function addConfiguration(NodeBuilder $persistenceConfig, NodeBuilder $builder)
    {
        $this->addPersistenceNode('phpcr', $persistenceConfig);
    }

    /**
     * {@inheritdoc}
     */
    public function create($persistence, array $config, ContainerBuilder $container, XmlFileLoader $loader)
    {
        $config['persistence'] = $this->useGlobalIfImplicit($persistence, $config['persistence']);

        if ($this->isConfigEnabled($container, $config['persistence']['phpcr'])) {
            $loader->load('content-phpcr.xml');
        }
    }
}
