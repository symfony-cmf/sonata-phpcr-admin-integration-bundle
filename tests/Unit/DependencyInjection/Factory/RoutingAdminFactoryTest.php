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
class RoutingAdminFactoryTest extends AbstractFactoryTest
{
    public function testParametersBundle()
    {
        $this->container->setParameter('kernel.bundles', [
            'CmfRoutingBundle' => true,
            'SonataDoctrinePHPCRAdminBundle' => true,
            'CmfSonataPhpcrAdminIntegrationBundle' => true,
        ]);
        $this->load([
            'bundles' => [
                'routing' => [
                    'enabled' => true,
                    'extensions' => [
                        'referrers' => ['form_group' => 'referrers_form_group'],
                    ],
                ],
            ],
        ]);

        $this->assertContainerBuilderHasParameter(
            'cmf_sonata_phpcr_admin_integration.routing.extension.referrers.from_group',
            'referrers_form_group'
        );
        $this->assertContainerBuilderHasParameter(
            'cmf_sonata_phpcr_admin_integration.routing.extension.referrers.from_tab',
            'form.tab_routes'
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
            ]
        );

        $this->load([
            'bundles' => [
                'routing' => true,
            ],
        ]);

        $this->assertContainerBuilderHasService(
            'cmf_sonata_phpcr_admin_integration.routing.route_admin'
        );
        $this->assertContainerBuilderHasService(
            'cmf_sonata_phpcr_admin_integration.routing.redirect_route_admin'
        );
        $this->assertContainerBuilderHasService(
            'cmf_sonata_phpcr_admin_integration.routing.extension.route_referrers'
        );
        $this->assertContainerBuilderHasService(
            'cmf_sonata_phpcr_admin_integration.routing.extension.frontend_link'
        );
    }
}
