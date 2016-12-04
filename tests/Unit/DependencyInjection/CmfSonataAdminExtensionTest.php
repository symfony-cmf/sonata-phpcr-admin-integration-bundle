<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SonataAdminIntegrationBundle\Tests\Unit\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Symfony\Cmf\Bundle\SonataAdminIntegrationBundle\DependencyInjection\CmfSonataAdminIntegrationExtension;

class CmfSonataAdminExtensionTest extends AbstractExtensionTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function getContainerExtensions()
    {
        return array(
            new CmfSonataAdminIntegrationExtension(),
        );
    }

    public function testDefaults()
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

        $this->load([
            'bundles' => [
                'seo' => [
                    'enabled' => true,
                    'form_group' => 'seo_form',
                ],
            ],
        ]);

        $this->assertContainerBuilderHasParameter('cmf_sonata_admin_integration.seo.form_group', 'seo_form');
    }

    public function testCoreDefaults()
    {

        $this->container->setParameter(
            'kernel.bundles',
            array(
                'CmfRoutingBundle' => true,
                'SonataDoctrinePHPCRAdminBundle' => true,
                'DoctrinePHPCRBundle' => true,
            )
        );

        $this->load([
            'bundles' => [
                'core' =>[
                    'enabled' => true,
                    'form_group' => 'core_form',
                ],
            ],
            'extensions' => [
                'publishable' => ['form_group' => 'publishable_form'],
                'publish_time' => ['form_group' => 'publish_time_form'],
                'translatable' => ['form_group' => 'translatable_form'],
            ]
        ]);

        $this->assertContainerBuilderHasParameter('cmf_sonata_admin_integration.core.form_group', 'core_form');
        $this->assertContainerBuilderHasParameter(
            'cmf_sonata_admin_integration.core.publishable.form_group',
            'publishable_form'
        );
        $this->assertContainerBuilderHasParameter(
            'cmf_sonata_admin_integration.core.publish_time.form_group',
            'publish_time_form'
        );
        $this->assertContainerBuilderHasParameter(
            'cmf_sonata_admin_integration.core.translatable.form_group',
            'translatable_form'
        );
    }
}
