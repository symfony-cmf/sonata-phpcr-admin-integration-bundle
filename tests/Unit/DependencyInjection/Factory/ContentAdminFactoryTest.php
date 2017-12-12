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

use Symfony\Cmf\Bundle\SonataPhpcrAdminIntegrationBundle\DependencyInjection\Factory\ContentAdminFactory;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * @author Wouter de Jong <wouter@wouterj.nl>
 * @author Maximilian Berghoff <Maximilian.Berghoff@mayflower.de>
 */
class ContentAdminFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $factory;

    private $container;

    private $fileLoader;

    protected function setUp()
    {
        $this->factory = new ContentAdminFactory();
        $this->container = new ContainerBuilder();
        $this->container->setParameter('kernel.bundles', ['IvoryCKEditorBundle' => true]);
        $this->fileLoader = $this->createMock(XmlFileLoader::class);
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     * @expectedExceptionMessage config_name setting has to be defined when IvoryCKEditorBundle integration is enabled
     */
    public function testInvalidCKEditorEnabledWithoutConfigName()
    {
        $config = $this->process($this->buildConfig(), [[
            'bundles' => [
                'content' => true,
            ],
        ]]);

        $this->create($config);
    }

    public function testCKEditorDisabledWithoutConfigName()
    {
        $config = $this->process($this->buildConfig(), [[
            'bundles' => [
                'content' => ['ivory_ckeditor' => false],
            ],
        ]]);

        $this->create($config);

        $this->assertEquals([], $this->container->getParameter('cmf_sonata_phpcr_admin_integration.content.ivory_ckeditor'));
    }

    public function testCKEditorEnabledWithConfigName()
    {
        $config = $this->process($this->buildConfig(), [[
            'bundles' => [
                'content' => ['ivory_ckeditor' => ['config_name' => 'default']],
            ],
        ]]);

        $this->create($config);

        $this->assertEquals(['config_name' => 'default'], $this->container->getParameter('cmf_sonata_phpcr_admin_integration.content.ivory_ckeditor'));
    }

    protected function buildConfig()
    {
        $treeBuilder = new TreeBuilder();
        $root = $treeBuilder->root('cmf_sonata_phpcr_admin_integration');

        $bundles = $root->children()->arrayNode('bundles')->isRequired()->children();
        $config = $bundles->arrayNode($this->factory->getKey())
            ->addDefaultsIfNotSet()
            ->canBeEnabled()
            ->children();

        $this->factory->addConfiguration($config);

        return $treeBuilder;
    }

    protected function process(TreeBuilder $treeBuilder, array $configs)
    {
        $processor = new Processor();

        return $processor->process($treeBuilder->buildTree(), $configs);
    }

    protected function create(array $processedConfig)
    {
        $this->factory->create($processedConfig['bundles'][$this->factory->getKey()], $this->container, $this->fileLoader);
    }
}
