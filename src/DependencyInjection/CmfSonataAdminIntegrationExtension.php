<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SonataAdminIntegrationBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Cmf\Bundle\SonataAdminIntegrationBundle\DependencyInjection\Factory\AdminFactoryInterface;

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@mayflower.de>
 * @author Wouter de Jong <wouter@wouterj.nl>
 */
class CmfSonataAdminIntegrationExtension extends Extension
{
    /**
     * @var AdminFactoryInterface[]
     */
    private $factories = [];

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $this->addDefaultFactories($container);

        $configuration = new Configuration($this->factories);
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $this->loadBundles($config['bundles'], $loader, $container);
    }

    private function loadBundles(array $config, XmlFileLoader $loader, $container)
    {
        foreach ($this->factories as $name => $factory) {
            if ($this->isConfigEnabled($container, $config[$name])) {
                $factory->create($config[$name], $container, $loader);
            }
        }
    }

    /**
     * Registers an admin factory.
     *
     * This method can be called in a bundle's build() method in order to add
     * new admin integrations.
     *
     * @param AdminFactoryInterface $factory
     */
    public function registerAdminFactory(AdminFactoryInterface $factory)
    {
        $this->factories[$factory->getKey()] = $factory;
    }

    /**
     * {@inheritdoc}
     */
    public function getNamespace()
    {
        return 'http://cmf.symfony.com/schema/dic/sonata-admin';
    }

    /**
     * {@inheritdoc}
     */
    public function getXsdValidationBasePath()
    {
        return __DIR__.'/../Resources/config/schema';
    }

    private function addDefaultFactories(ContainerBuilder $container)
    {
        $bundles = [
            'CmfSeoBundle' => new Factory\SeoAdminFactory(),
            'CmfMenuBundle' => new Factory\MenuAdminFactory(),
        ];
        $enabledBundles = $container->getParameter('kernel.bundles');

        foreach ($bundles as $bundleName => $factory) {
            if (isset($enabledBundles[$bundleName])) {
                $this->registerAdminFactory($factory);
            }
        }
    }
}
