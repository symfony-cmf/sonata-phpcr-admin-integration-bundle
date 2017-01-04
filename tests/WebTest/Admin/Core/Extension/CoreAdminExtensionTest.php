<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SonataPhpcrAdminIntegrationBundle\Tests\WebTest\Admin\Core\Extension;

use Symfony\Cmf\Bundle\SonataPhpcrAdminIntegrationBundle\Tests\WebTest\Admin\TestCase;

/**
 * This test will cover all behavior with the provides admin extension.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class CoreAdminExtensionTest extends TestCase
{
    public function setUp()
    {
        $this->db('PHPCR')->loadFixtures(array(
            'Symfony\Cmf\Bundle\SonataPhpcrAdminIntegrationBundle\Tests\Resources\DataFixtures\Phpcr\LoadCoreData',
        ));
        $this->client = $this->createClient();
    }

    public function testItemEditView()
    {
        $crawler = $this->getClient()->request('GET', '/admin/cmf/core/extensions/test/core/with-extensions/edit');

        $this->assertResponseSuccess($this->getClient()->getResponse());

        $this->assertCount(1, $crawler->filter('html:contains("Publishable")'));
        $this->assertCount(1, $crawler->filter('html:contains("Publish from")'));
        $this->assertCount(1, $crawler->filter('html:contains("Publish until")'));
    }

    public function testItemCreate()
    {
        $crawler = $this->client->request('GET', '/admin/cmf/core/extensions/create');

        $this->assertResponseSuccess($this->getClient()->getResponse());

        $this->assertCount(1, $crawler->filter('html:contains("Publishable")'));
        $this->assertCount(1, $crawler->filter('html:contains("Publish from")'));
        $this->assertCount(1, $crawler->filter('html:contains("Publish until")'));
    }
}
