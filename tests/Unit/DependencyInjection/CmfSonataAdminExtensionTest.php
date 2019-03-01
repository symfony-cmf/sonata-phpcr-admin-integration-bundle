<?php

declare(strict_types=1);

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SonataPhpcrAdminIntegrationBundle\Tests\Unit\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Symfony\Cmf\Bundle\SonataPhpcrAdminIntegrationBundle\DependencyInjection\CmfSonataPhpcrAdminIntegrationExtension;

class CmfSonataAdminExtensionTest extends AbstractExtensionTestCase
{
    public function testThatBundlesAreConfigured()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\InvalidConfigurationException::class);

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

        $this->load([]);
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

    /**
     * {@inheritdoc}
     */
    protected function getContainerExtensions()
    {
        return [
            new CmfSonataPhpcrAdminIntegrationExtension(),
        ];
    }
}
