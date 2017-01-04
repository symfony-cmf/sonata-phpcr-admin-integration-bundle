<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SonataPhpcrAdminIntegrationBundle\Tests\Unit\DependencyInjection;

use Symfony\Cmf\Component\Testing\Unit\XmlSchemaTestCase;

class XmlSchemaTest extends XmlSchemaTestCase
{
    public function testSchema()
    {
        $xmlFiles = array_map(function ($file) {
            return __DIR__.'/../../Resources/Fixtures/config/'.$file;
        }, ['config.xml']);

        foreach ($xmlFiles as $xmlFile) {
            $this->assertSchemaAcceptsXml([$xmlFile], __DIR__.'/../../../src/Resources/config/schema/sonata-phpcr-admin-integration.xsd');
        }
    }
}
