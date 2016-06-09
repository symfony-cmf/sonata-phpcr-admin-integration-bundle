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

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@mayflower.de>
 */
class CmfSonataAdminIntegrationExtension extends Extension
{

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $this->loadBundles($config['bundles'], $loader, $container);
    }

    private function loadBundles(array $config, XmlFileLoader $loader, $container)
    {
        $bundles = $container->getParameter('kernel.bundles');
        foreach (['seo'] as $name) {
            if ($this->isConfigEnabled($container, $config[$name])) {
                $loader->load($name.'.xml');

                if ('seo' === $name && !isset($bundles['BurgovKeyValueFormBundle'])) {
                    throw new InvalidConfigurationException(
                        'To use advanced menu options, you need the burgov/key-value-form-bundle in your project.'
                    );
                }
                $container->setParameter(sprintf('cmf_sonata_admin_integration.%s.form_group', $name), $config[$name]['form_group']);
            }
        }
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
}
