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
class SeoAdminFactoryTest extends AbstractFactoryTest
{
    public function testParametersBundle()
    {
        $this->container->setParameter(
            'kernel.bundles',
            [
                'CmfSeoBundle' => true,
                'CmfRoutingBundle' => true,
                'SonataDoctrinePHPCRAdminBundle' => true,
                'DoctrinePHPCRBundle' => true,
                'BurgovKeyValueFormBundle' => true,
            ]
        );

        $this->load([
            'bundles' => [
                'seo' => [
                    'enabled' => true,
                    'extensions' => [
                        'metadata' => [
                            'form_group' => 'seo_group',
                            'form_tab' => 'seo_tab',
                        ],
                    ],
                ],
            ],
        ]);

        $this->assertContainerBuilderHasParameter('cmf_sonata_phpcr_admin_integration.seo.extension.metadata.form_group', 'seo_group');
        $this->assertContainerBuilderHasParameter('cmf_sonata_phpcr_admin_integration.seo.extension.metadata.form_tab', 'seo_tab');
    }

    public function testAdminServicesBundle()
    {
        $this->container->setParameter(
            'kernel.bundles',
            [
                'CmfSeoBundle' => true,
                'CmfRoutingBundle' => true,
                'SonataDoctrinePHPCRAdminBundle' => true,
                'DoctrinePHPCRBundle' => true,
                'BurgovKeyValueFormBundle' => true,
            ]
        );

        $this->load([
            'bundles' => [
                'seo' => true,
            ],
        ]);

        $this->assertContainerBuilderHasService('cmf_sonata_phpcr_admin_integration.seo.extension.metadata');
        $this->assertContainerBuilderHasService('cmf_sonata_phpcr_admin_integration.seo.extension.metadata');
    }
}
