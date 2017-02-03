<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SonataPhpcrAdminIntegrationBundle\Tests\WebTest\Admin;

use Symfony\Cmf\Component\Testing\Functional\BaseTestCase;
use Symfony\Cmf\Bundle\SonataPhpcrAdminIntegrationBundle\Tests\Resources\DataFixtures\Phpcr\LoadContentData;

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

    abstract public function testItemEditView();

    abstract public function testItemCreate();
}
