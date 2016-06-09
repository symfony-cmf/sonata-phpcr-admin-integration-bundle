<?php

namespace Symfony\Cmf\Bundle\SonataAdminIntegrationBundle\Tests\WebTest\Admin;

use Symfony\Cmf\Component\Testing\Functional\BaseTestCase;
use Symfony\Cmf\Bundle\SonataAdminIntegrationBundle\Tests\Resources\DataFixtures\Phpcr\LoadContentData;

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@mayflower.de>
 */
abstract class TestCase extends BaseTestCase
{
    public function setUp()
    {
        $this->db('PHPCR')->loadFixtures(array(
            LoadContentData::class,
        ));
    }

    public function testAdminDashboard()
    {
        $this->getClient()->request('GET', '/admin/dashboard');

        $this->assertResponseSuccess($this->getClient()->getResponse());
    }

    abstract public function testAdminExtensionExists();
    abstract public function testItemEditView();
    abstract public function testItemCreate();
}
