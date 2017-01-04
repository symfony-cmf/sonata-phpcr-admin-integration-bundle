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
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * Admin factories enable new admin classes to be made available by this
 * integration.
 *
 * @author Wouter de Jong <wouter@wouterj.nl>
 */
interface AdminFactoryInterface
{
    /**
     * The configuration key used to reference the admin.
     *
     * @return string
     */
    public function getKey();

    /**
     * Defines additional configuration options.
     *
     * These are available under cmf_sonata_admin_integration.bundles.<name>.
     * The `enabled` option is always available.
     *
     * @param NodeBuilder $builder
     */
    public function addConfiguration(NodeBuilder $builder);

    /**
     * Creates the admin services based on the configured options.
     *
     * @param array            $config
     * @param ContainerBuilder $container
     */
    public function create(array $config, ContainerBuilder $container, XmlFileLoader $loader);
}
