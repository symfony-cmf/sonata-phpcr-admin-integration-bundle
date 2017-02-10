<?php

namespace Symfony\Cmf\Bundle\SonataPhpcrAdminIntegrationBundle\Tests\Unit\DependencyInjection\Factory;

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@mayflower.de>
 */
class BlockAdminFactoryTest extends AbstractFactoryTest
{
    public function testParametersBundle()
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
                    'use_imagine' => true,
                    'basepath' => 'basepath_value',
                    'menu_basepath' => 'menu_basepath_value',
                    'extensions' => [
                        'block_cache' => [
                           'form_group' => 'block_group',
                            'form_tab' => 'block_tab',
                        ],
                    ],
                ],
            ],
        ]);

        $this->assertContainerBuilderHasParameter('cmf_sonata_phpcr_admin_integration.block.persistence.basepath', 'basepath_value');
        $this->assertContainerBuilderHasParameter('cmf_sonata_phpcr_admin_integration.block.persistence.menu_basepath', 'menu_basepath_value');
        $this->assertContainerBuilderHasParameter('cmf_sonata_phpcr_admin_integration.block.extension.block_cache.form_group', 'block_group');
        $this->assertContainerBuilderHasParameter('cmf_sonata_phpcr_admin_integration.block.extension.block_cache.form_tab', 'block_tab');
    }

    public function testAdminServicesBundle()
    {
        $this->container->setParameter(
            'kernel.bundles',
            [
                'CmfBlockBundle' => true,
                'CmfRoutingBundle' => true,
                'SonataDoctrinePHPCRAdminBundle' => true,
                'DoctrinePHPCRBundle' => true,
            ]
        );

        $this->load([
            'bundles' => [
                'block' => true,
            ],
        ]);

        $this->assertContainerBuilderHasService('cmf_sonata_phpcr_admin_integration.block.admin_extension.cache');
        $this->assertContainerBuilderHasService('cmf_sonata_phpcr_admin_integration.block.simple_admin');
        $this->assertContainerBuilderHasService('cmf_sonata_phpcr_admin_integration.block.action_admin');
        $this->assertContainerBuilderHasService('cmf_sonata_phpcr_admin_integration.block.container_admin');
        $this->assertContainerBuilderHasService('cmf_sonata_phpcr_admin_integration.block.reference_admin');
        $this->assertContainerBuilderHasService('cmf_sonata_phpcr_admin_integration.block.string_admin');
    }

    public function testMenuAdminServicesBundle()
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
                    'enable_menu' => 'auto'
                ]
            ],
        ]);

        $this->assertContainerBuilderHasService('cmf_sonata_phpcr_admin_integration.block.menu_admin');
    }
}
