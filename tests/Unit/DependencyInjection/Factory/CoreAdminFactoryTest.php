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
class CoreAdminFactoryTest extends AbstractFactoryTest
{
    public function testParametersBundle()
    {
        $this->container->setParameter('kernel.bundles', [
            'CmfCoreBundle' => true,
            'SonataDoctrinePHPCRAdminBundle' => true,
            'CmfSonataPhpcrAdminIntegrationBundle' => true,
        ]);
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
    }

    public function testAdminServicesBundle()
    {
        $this->container->setParameter(
            'kernel.bundles',
            [
                'CmfRoutingBundle' => true,
                'SonataDoctrinePHPCRAdminBundle' => true,
                'DoctrinePHPCRBundle' => true,
                'CmfCoreBundle' => true,
            ]
        );

        $this->load([
            'bundles' => [
                'core' => true,
            ],
        ]);

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
}
