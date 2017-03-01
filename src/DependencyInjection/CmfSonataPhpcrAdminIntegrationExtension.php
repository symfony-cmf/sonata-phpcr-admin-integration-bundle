<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SonataPhpcrAdminIntegrationBundle\DependencyInjection;

use Ivory\CKEditorBundle\IvoryCKEditorBundle;
use Symfony\Cmf\Bundle\SonataPhpcrAdminIntegrationBundle\DependencyInjection\Factory\IsConfigEnabledTrait;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Cmf\Bundle\SonataPhpcrAdminIntegrationBundle\DependencyInjection\Factory\AdminFactoryInterface;

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@mayflower.de>
 * @author Wouter de Jong <wouter@wouterj.nl>
 */
class CmfSonataPhpcrAdminIntegrationExtension extends Extension implements CompilerPassInterface
{
    use IsConfigEnabledTrait;

    /**
     * @var AdminFactoryInterface[]
     */
    private $factories = [];

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        foreach ($this->factories as $factory) {
            $container->addObjectResource($factory);
            if ($factory instanceof CompilerPassInterface) {
                $factory->process($container);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = $this->getConfiguration([], $container);
        $config = $this->processConfiguration($configuration, $configs);
        $bundles = $container->getParameter('kernel.bundles');

        if (!isset($bundles['SonataDoctrinePHPCRAdminBundle'])) {
            throw new \LogicException('The SonataDoctrinePhpcrAdminBundle must be registered in order to use the CmfSonataPhpcrAdminIntegrationBundle.');
        }

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $this->loadBundles($config['bundles'], $loader, $container);

        $loader->load('main.xml');
        $loader->load('enhancer.xml');

        $this->loadIvoryCKEditor($config['ivory_ckeditor'], $container);
    }

    /**
     * Adds the ckEditor configuration to the parameters list when configuration is enabled (auto, true). For both
     * settings the "egeloen/ckeditor-bundle" has to be installed and enabled.
     *
     * @param array            $config
     * @param ContainerBuilder $container
     *
     * @throws \LogicException When configuration is enabled by true and "IvoryCKEditorBundle" is not enabled.
     */
    protected function loadIvoryCKEditor(array $config, ContainerBuilder $container)
    {
        $configParameter = [];
        if ($this->isConfigEnabled($container, $config)) {
            $bundles = $container->getParameter('kernel.bundles');
            if (true === $config['enabled'] && !isset($bundles['IvoryCKEditorBundle'])) {
                $message = 'IvoryCKEditorBundle integration was explicitely enabled, but the bundle is not available';
                if (class_exists(IvoryCKEditorBundle::class)) {
                    $message .= ' (did you forget to register the bundle in the AppKernel?)';
                }
                throw new \LogicException($message.'.');
            } elseif (isset($bundles['IvoryCKEditorBundle'])) {
                $configParameter = ['config_name' => $config['config_name']];
            }
        }

        $container->setParameter('cmf_sonata_phpcr_admin_integration.ivory_ckeditor.config', $configParameter);
    }

    /**
     * {@inheritdoc}
     *
     * Overwritten because configuration can not be auto instantiated as it has a constructor.
     */
    public function getConfiguration(array $config, ContainerBuilder $container)
    {
        $this->addDefaultFactories($container);

        return new Configuration($this->factories);
    }

    private function loadBundles(array $config, XmlFileLoader $loader, ContainerBuilder $container)
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
        return 'http://cmf.symfony.com/schema/dic/sonata-phpcr-admin-integration';
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
            'CmfRoutingBundle' => new Factory\RoutingAdminFactory(),
            'CmfBlockBundle' => new Factory\BlockAdminFactory(),
            'CmfCoreBundle' => new Factory\CoreAdminFactory(),
            'CmfContentBundle' => new Factory\ContentAdminFactory(),
        ];
        $enabledBundles = $container->getParameter('kernel.bundles');

        foreach ($bundles as $bundleName => $factory) {
            if (isset($enabledBundles[$bundleName])) {
                $this->registerAdminFactory($factory);
            }
        }
    }
}
