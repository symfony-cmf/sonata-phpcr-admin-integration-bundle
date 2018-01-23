<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SonataPhpcrAdminIntegrationBundle\Tests\Fixtures\App\DataFixtures\Phpcr;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PHPCR\Util\NodeHelper;
use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Phpcr\Route;
use Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr\SeoMetadata;
use Symfony\Cmf\Bundle\SonataPhpcrAdminIntegrationBundle\Tests\Fixtures\App\Document\SeoAwareContent;

class LoadContentData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        NodeHelper::createPath($manager->getPhpcrSession(), '/test');

        NodeHelper::createPath($manager->getPhpcrSession(), '/test/content');
        NodeHelper::createPath($manager->getPhpcrSession(), '/test/routes/content');

        $contentRoot = $manager->find(null, '/test/content');
        $routeRoot = $manager->find(null, '/test/routes/content');

        $content = new SeoAwareContent();
        $content->setName('content-1');
        $content->setTitle('Content 1');
        $content->setBody('Content 1');
        $content->setParentDocument($contentRoot);

        $metadata = new SeoMetadata();
        $metadata->setTitle('Title content 1');
        $metadata->setMetaDescription('Description of content 1.');
        $metadata->setMetaKeywords('content1, content');
        $metadata->setOriginalUrl('/to/original');

        $content->setSeoMetadata($metadata);
        $manager->persist($content);

        $route = new Route();
        $route->setPosition($routeRoot, 'content-1');
        $route->setContent($content);
        $route->setDefault('_controller', 'Symfony\Cmf\Bundle\SeoBundle\Tests\Fixtures\App\Controller\TestController::indexAction');

        $manager->persist($route);

        $manager->persist($route);

        $manager->flush();
    }
}
