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
 * @author Wouter de Jong <wouter@wouterj.nl>
 */
class ContentAdminFactory implements AdminFactoryInterface
{
    use IsConfigEnabledTrait;

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
    public function addConfiguration(NodeBuilder $builder)
    {
        $builder
            ->arrayNode('ivory_ckeditor')
                ->addDefaultsIfNotSet()
                ->canBeEnabled()
                ->children()
                    ->scalarNode('config_name')->defaultValue('cmf_content')->end()
                ->end()
            ->end();
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $config, ContainerBuilder $container, XmlFileLoader $loader)
    {
        $loader->load('content.xml');
        $this->loadIvoryCKEditor($config['ivory_ckeditor'], $container);
    }

    protected function loadIvoryCKEditor(array $config, ContainerBuilder $container)
    {
        $container->setParameter('cmf_sonata_phpcr_admin_integration.content.ivory_ckeditor.config', []);
        $bundles = $container->getParameter('kernel.bundles');
        if ('auto' === $config['enabled'] && !isset($bundles['IvoryCKEditorBundle'])) {
            return;
        }

        if ($this->isConfigEnabled($container, $config) && !isset($bundles['IvoryCKEditorBundle'])) {
            $message = 'IvoryCKEditorBundle integration was explicitely enabled, but the bundle is not available';
            if (class_exists('Ivory\CKEditorBundle\IvoryCKEditorBundle')) {
                $message .= ' (did you forget to register the bundle in the AppKernel?)';
            }
            throw new \LogicException($message.'.');
        }

        if (!$this->isConfigEnabled($container, $config) || !isset($bundles['IvoryCKEditorBundle'])) {
            return;
        }

        $container->setParameter(
            'cmf_sonata_phpcr_admin_integration.content.ivory_ckeditor.config',
            ['config_name' => $config['config_name']]
        );
    }
}
