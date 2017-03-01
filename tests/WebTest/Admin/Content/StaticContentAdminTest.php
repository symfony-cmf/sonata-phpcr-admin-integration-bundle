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
use Symfony\Cmf\Bundle\SonataPhpcrAdminIntegrationBundle\Tests\Resources\DataFixtures\Phpcr\LoadStaticContentData;

class StaticContentAdminTest extends BaseTestCase
{
    public function setUp()
    {
        $this->db('PHPCR')->loadFixtures([LoadStaticContentData::class]);
        $this->client = $this->createClient();
    }

    public function testContentList()
    {
        $crawler = $this->client->request('GET', '/admin/cmf/content/staticcontent/list');
        $res = $this->client->getResponse();

        $this->assertResponseSuccess($res);
        $this->assertCount(1, $crawler->filter('html:contains("Content 1")'));
    }

    public function testContentEdit()
    {
        $crawler = $this->client->request('GET', '/admin/cmf/content/staticcontent/test/contents/content-1/edit');
        $res = $this->client->getResponse();

        $this->assertResponseSuccess($res);
        $this->assertCount(1, $crawler->filter('input[value="content-1"]'));
        $this->assertCount(1, $crawler->filter('script[src*="ckeditor.js"]'));
    }

    public function testContentCreate()
    {
        $crawler = $this->client->request('GET', '/admin/cmf/content/staticcontent/create');
        $res = $this->client->getResponse();
        $this->assertResponseSuccess($res);

        $button = $crawler->selectButton('Create');
        $form = $button->form();
        $node = $form->getFormNode();
        $actionUrl = $node->getAttribute('action');
        $uniqId = substr(strstr($actionUrl, '='), 1);

        $form[$uniqId.'[parentDocument]'] = '/test/contents';
        $form[$uniqId.'[name]'] = 'foo-test';
        $form[$uniqId.'[title]'] = 'Foo Test';
        $form[$uniqId.'[body]'] = 'Foo Test';

        $this->client->submit($form);
        $res = $this->client->getResponse();

        // If we have a 302 redirect, then all is well
        $this->assertEquals(302, $res->getStatusCode());
    }
}
