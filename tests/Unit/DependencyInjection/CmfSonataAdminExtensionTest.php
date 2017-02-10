<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SonataPhpcrAdminIntegrationBundle\Tests\Unit\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Symfony\Cmf\Bundle\SonataPhpcrAdminIntegrationBundle\DependencyInjection\CmfSonataPhpcrAdminIntegrationExtension;

class CmfSonataAdminExtensionTest extends AbstractExtensionTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function getContainerExtensions()
    {
        return array(
            new CmfSonataPhpcrAdminIntegrationExtension(),
        );
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testThatBundlesAreConfigured()
    {
        $this->container->setParameter(
            'kernel.bundles',
            array(
                'CmfSeoBundle' => true,
                'CmfRoutingBundle' => true,
                'SonataDoctrinePHPCRAdminBundle' => true,
                'DoctrinePHPCRBundle' => true,
                'BurgovKeyValueFormBundle' => true,
            )
        );

        $this->load([]);
    }

    public function testBlockBundle()
    {
        $this->container->setParameter(
            'kernel.bundles',
            [
                'CmfBlockBundle' => true,
                'CmfMenuBundle' => true,
                'CmfRoutingBundle' => true,
                'SonataDoctrinePHPCRAdminBundle' => true,
                'DoctrinePHPCRBundle' => true,
            ]
        );

        $this->load([
            'bundles' => [
                'block' => [
                    'enabled' => true,
                    'basepath' => 'basepath_value',
                    'menu_basepath' => 'menu_basepath_value',
                ],
            ],
        ]);

        $this->assertContainerBuilderHasParameter('cmf_sonata_phpcr_admin_integration.block.persistence.basepath', 'basepath_value');
        $this->assertContainerBuilderHasParameter('cmf_sonata_phpcr_admin_integration.block.persistence.menu_basepath', 'menu_basepath_value');
    }

    public function testCoreDefaults()
    {
        $this->container->setParameter(
            'kernel.bundles',
            array(
                'CmfRoutingBundle' => true,
                'SonataDoctrinePHPCRAdminBundle' => true,
                'DoctrinePHPCRBundle' => true,
                'CmfCoreBundle' => true,
            )
        );

        $this->load([
            'bundles' => [
                'core' => [
                    'enabled' => true,
                    'extensions' => [
                        'publishable' => ['form_group' => 'publishable_form'],
                        'publish_time' => ['form_group' => 'publish_time_form'],
                    ],
                ],
            ],
        ]);

        $this->assertContainerBuilderHasParameter(
            'cmf_sonata_phpcr_admin_integration.core.extension.publishable.form_group',
            'publishable_form'
        );
        $this->assertContainerBuilderHasParameter(
            'cmf_sonata_phpcr_admin_integration.core.extension.publish_time.form_group',
            'publish_time_form'
        );

        $this->assertContainerBuilderHasService(
            'cmf_sonata_phpcr_admin_integration.core.extension.publish_workflow.time_period'
        );
        $this->assertContainerBuilderHasService(
            'cmf_sonata_phpcr_admin_integration.core.extension.publish_workflow.publishable'
        );
        $this->assertContainerBuilderHasService(
            'cmf_sonata_phpcr_admin_integration.core.extension.child'
        );
    }

    public function testEnhancerExists()
    {
        $this->container->setParameter(
            'kernel.bundles',
            [
                'SonataDoctrinePHPCRAdminBundle' => true,
                'SonataAdminBundle' => true,
            ]
        );

        $this->load(['bundles' => []]);

        $this->assertContainerBuilderHasServiceDefinitionWithTag(
            'cmf_sonata_phpcr_admin_integration.description.enhancer',
            'cmf_resource.description.enhancer',
            ['alias' => 'sonata_phpcr_admin']
        );
    }
}
