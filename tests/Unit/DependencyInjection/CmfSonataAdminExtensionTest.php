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
use Symfony\Cmf\Bundle\SeoBundle\DependencyInjection\CmfSeoExtension;
use Symfony\Cmf\Bundle\SonataAdminIntegrationBundle\DependencyInjection\CmfSonataAdminIntegrationExtension;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;

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

    public function testSeo()
    {
        $this->container->setParameter(
            'kernel.bundles',
            array(
                'CmfRoutingBundle' => true,
                'SonataDoctrinePHPCRAdminBundle' => true,
                'DoctrinePHPCRBundle' => true,
                'BurgovKeyValueFormBundle' => true,
            )
        );

        $this->load([
            'dynamic' => [
                'persistence' => 'phpcr',
            ],
            'bundles' => [
                'seo' =>[
                    'enabled' => true,
                    'form_group' => 'seo_form'
                ],
            ]
        ]);

        $this->assertContainerBuilderHasParameter('cmf_sonata_admin_integration.seo.form_group', 'seo_form');
        $this->assertContainerBuilderHasServiceDefinitionWithTag(
            'cmf_sonata_admin_integration.seo.admin_extension',
            'sonata.admin.extension'
        );
    }

    public function testRoute()
    {
        $this->container->setParameter(
            'kernel.bundles',
            array(
                'CmfRoutingBundle' => true,
                'SonataDoctrinePHPCRAdminBundle' => true,
                'DoctrinePHPCRBundle' => true,
                'BurgovKeyValueFormBundle' => true,
            )
        );

        $this->load([
            'dynamic' => [
                'persistence' => 'phpcr',
            ],
            'bundles' => [
                'route' =>[
                    'enabled' => true,
                    'form_group' => 'route_form',
                    'admin_basepath' => '/some/path'
                ],
            ]
        ]);

        $this->assertContainerBuilderHasParameter('cmf_sonata_admin_integration.route.form_group', 'route_form');
        $this->assertContainerBuilderHasParameter('cmf_sonata_admin_integration.route.admin_basepath', '/some/path');
        $this->assertContainerBuilderHasServiceDefinitionWithTag(
            'cmf_sonata_admin_integration.route.extension.route_referrers',
            'sonata.admin.extension'
        );
        $this->assertContainerBuilderHasServiceDefinitionWithTag(
            'cmf_sonata_admin_integration.route.extension.frontend_link',
            'sonata.admin.extension'
        );
        $this->assertContainerBuilderHasService(
            'cmf_sonata_admin_integration.route.admin',
            'Symfony\Cmf\Bundle\SonataAdminIntegrationBundle\Admin\Route\RouteAdmin'
        );
        $this->assertContainerBuilderHasService(
            'cmf_sonata_admin_integration.route.redirect_route_admin',
            'Symfony\Cmf\Bundle\SonataAdminIntegrationBundle\Admin\Route\RedirectRouteAdmin'
        );
    }
}
