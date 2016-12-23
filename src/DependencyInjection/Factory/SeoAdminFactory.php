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
class SeoAdminFactory implements AdminFactoryInterface
{
    use PersistenceTrait;

    /**
     * {@inheritdoc}
     */
    public function getKey()
    {
        return 'seo';
    }

    /**
     * {@inheritdoc}
     */
    public function addConfiguration(NodeBuilder $persistenceBuilder, NodeBuilder $builder)
    {
        $this->addPersistenceNode('phpcr', $persistenceBuilder);
        $this->addPersistenceNode('orm', $persistenceBuilder);

        $builder
            ->scalarNode('form_group')->defaultValue('form.group_seo')->end()
            ->scalarNode('form_tab')->defaultValue('form.tab_seo')->end()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function create($persistence, array $config, ContainerBuilder $container, XmlFileLoader $loader)
    {
        $config['persistence'] = $this->useGlobalIfImplicit($persistence, $config['persistence']);

        if ($this->isConfigEnabled($container, $config['persistence']['phpcr'])
            || $this->isConfigEnabled($container, $config['persistence']['orm'])
        ) {
            $loader->load('seo.xml');

            $container->setParameter('cmf_sonata_admin_integration.seo.form_group', $config['form_group']);
            $container->setParameter('cmf_sonata_admin_integration.seo.form_tab', $config['form_tab']);
        }
    }
}
