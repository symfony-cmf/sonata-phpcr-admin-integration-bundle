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
                    'use_imagine' => true,
                    'basepath' => 'basepath_value',
                    'menu_basepath' => 'menu_basepath_value',
                    'admin_classes' => [
                        'string_document_class' => 'string_document_class_value',
                        'simple_document_class' => 'simple_document_class_value',
                        'container_document_class' => 'container_document_class_value',
                        'reference_document_class' => 'reference_document_class_value',
                        'menu_document_class' => 'menu_document_class_value',
                        'action_document_class' => 'action_document_class_value',
                        'imagine_document_class' => 'imagine_document_class_value',
                        'slideshow_document_class' => 'slideshow_document_class_value',
                        'string_admin_class' => 'string_admin_class_value',
                        'simple_admin_class' => 'simple_admin_class_value',
                        'container_admin_class' => 'container_admin_class_value',
                        'reference_admin_class' => 'reference_admin_class_value',
                        'menu_admin_class' => 'menu_admin_class_value',
                        'action_admin_class' => 'action_admin_class_value',
                        'imagine_admin_class' => 'imagine_admin_class_value',
                        'slideshow_admin_class' => 'slideshow_admin_class_value',
                    ],
                ],
            ],
        ]);

        $this->assertContainerBuilderHasParameter('cmf_sonata_admin_integration.block.phpcr.basepath', 'basepath_value');
        $this->assertContainerBuilderHasParameter(
            'cmf_sonata_admin_integration.block.phpcr.menu_basepath',
            'menu_basepath_value'
        );

        $keys = [
            'string_document.class' => 'string_document_class_value',
            'simple_document.class' => 'simple_document_class_value',
            'container_document.class' => 'container_document_class_value',
            'reference_document.class' => 'reference_document_class_value',
            'menu_document.class' => 'menu_document_class_value',
            'action_document.class' => 'action_document_class_value',
            'imagine_document.class' => 'imagine_document_class_value',
            'slideshow_document.class' => 'slideshow_document_class_value',
            'string_admin.class' => 'string_admin_class_value',
            'simple_admin.class' => 'simple_admin_class_value',
            'container_admin.class' => 'container_admin_class_value',
            'reference_admin.class' => 'reference_admin_class_value',
            'menu_admin.class' => 'menu_admin_class_value',
            'action_admin.class' => 'action_admin_class_value',
            'imagine_admin.class' => 'imagine_admin_class_value',
            'slideshow_admin.class' => 'slideshow_admin_class_value',
        ];

        foreach ($keys as $suffix => $className) {
            $this->assertContainerBuilderHasParameter(
                'cmf_sonata_admin_integration.block.'.$suffix,
                $className
            );
            if (preg_match('/_document/', $suffix)) {
                continue;
            }
            if (in_array($suffix, ['imagine_admin.class', 'slideshow_admin.class'])) {
                $suffix = 'imagine.'.$suffix;
            }
            $this->assertContainerBuilderHasService(
                'cmf_sonata_admin_integration.block.'.str_replace('.class', '', $suffix),
                $className
            );
        }
    }
}
