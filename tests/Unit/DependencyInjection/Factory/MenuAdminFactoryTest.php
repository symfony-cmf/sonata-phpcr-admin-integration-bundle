<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SonataPhpcrAdminIntegrationBundle\Tests\Unit\DependencyInjection\Factory;

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@mayflower.de>
 */
class MenuAdminFactoryTest extends AbstractFactoryTest
{
    public function testParametersBundle()
    {
        $this->container->setParameter('kernel.bundles', [
            'CmfMenuBundle' => true,
            'SonataDoctrinePHPCRAdminBundle' => true,
            'CmfSonataPhpcrAdminIntegrationBundle' => true,
        ]);
        $this->load([
            'bundles' => [
                'menu' => [
                    'enabled' => true,
                    'extensions' => [
                        'menu_node_referrers' => ['form_group' => 'node_referrers_form_group'],
                        'menu_options' => ['form_group' => 'menu_options_form_group'],
                    ],
                ],
            ],
        ]);

        $this->assertContainerBuilderHasParameter(
            'cmf_sonata_phpcr_admin_integration.menu.extension.menu_node_referrers.form_group',
            'node_referrers_form_group'
        );
        $this->assertContainerBuilderHasParameter(
            'cmf_sonata_phpcr_admin_integration.menu.extension.menu_node_referrers.form_tab',
            'form.tab_menu'
        );

        $this->assertContainerBuilderHasParameter(
            'cmf_sonata_phpcr_admin_integration.menu.extension.menu_options.form_group',
            'menu_options_form_group'
        );
        $this->assertContainerBuilderHasParameter(
            'cmf_sonata_phpcr_admin_integration.menu.extension.menu_options.form_tab',
            'form.tab_general'
        );
        $this->assertContainerBuilderHasParameter(
            'cmf_sonata_phpcr_admin_integration.menu.extension.menu_options.advanced',
            false
        );
    }

    public function testAdminServicesBundle()
    {
        $this->container->setParameter(
            'kernel.bundles',
            [
                'CmfRoutingBundle' => true,
                'SonataDoctrinePHPCRAdminBundle' => true,
                'DoctrinePHPCRBundle' => true,
                'CmfMenuBundle' => true,
            ]
        );

        $this->load([
            'bundles' => [
                'menu' => true,
            ],
        ]);

        $this->assertContainerBuilderHasService(
            'cmf_sonata_phpcr_admin_integration.menu.extension.menu_node_referrers'
        );
        $this->assertContainerBuilderHasService(
            'cmf_sonata_phpcr_admin_integration.menu.extension.menu_options'
        );
        $this->assertContainerBuilderHasService(
            'cmf_sonata_phpcr_admin_integration.menu.menu_admin'
        );
        $this->assertContainerBuilderHasService(
            'cmf_sonata_phpcr_admin_integration.menu.node_admin'
        );
    }
}
