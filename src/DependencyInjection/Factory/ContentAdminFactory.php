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

use FOS\CKEditorBundle\FOSCKEditorBundle;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
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
            ->arrayNode('fos_ckeditor')
                ->treatFalseLike(['enabled' => false])
                ->treatTrueLike(['enabled' => true])
                ->treatNullLike(['enabled' => 'auto'])
                ->addDefaultsIfNotSet()
                ->ignoreExtraKeys()
                ->children()
                    ->enumNode('enabled')
                        ->values([true, false, 'auto'])
                        ->defaultValue('auto')
                    ->end()
                    ->scalarNode('config_name')->end()
                ->end()
            ->end()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $config, ContainerBuilder $container, XmlFileLoader $loader)
    {
        $loader->load('content.xml');

        $message = 'FOSCKEditorBundle integration was explicitely enabled, but the bundle is not available.';
        if (class_exists(FOSCKEditorBundle::class)) {
            $message .= ' (did you forget to register the bundle in the AppKernel?)';
        }

        $ckeditorConfig = [];
        if ($this->isConfigEnabledAuto($container, $config['fos_ckeditor']['enabled'], 'FOSCKEditorBundle', $message)) {
            if (!isset($config['fos_ckeditor']['config_name'])) {
                throw new InvalidConfigurationException('The cmf_sonata_phpcr_admin_integration.bundles.content.fos_ckeditor.config_name setting has to be defined when FOSCKEditorBundle integration is enabled.');
            }

            unset($config['fos_ckeditor']['enabled']);

            $ckeditorConfig = $config['fos_ckeditor'];
        }

        $container->setParameter('cmf_sonata_phpcr_admin_integration.content.fos_ckeditor', $ckeditorConfig);
    }
}
