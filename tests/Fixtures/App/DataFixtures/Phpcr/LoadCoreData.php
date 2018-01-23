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
use Doctrine\ODM\PHPCR\Document\Generic;
use PHPCR\Util\NodeHelper;
use Symfony\Cmf\Bundle\SonataPhpcrAdminIntegrationBundle\Tests\Fixtures\App\Document\CoreExtensionsAwareContent;

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@mayflower.de>
 */
class LoadCoreData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        NodeHelper::createPath($manager->getPhpcrSession(), '/test');

        $root = $manager->find(null, '/test');
        $parent = new Generic();
        $parent->setParentDocument($root);
        $parent->setNodename('core');
        $manager->persist($parent);

        $coreContent = new CoreExtensionsAwareContent();
        $coreContent->setParentDocument($parent);
        $coreContent->setName('with-extensions');
        $coreContent->setTitle('with-extensions');
        $coreContent->setBody('with-extensions');
        $manager->persist($coreContent);

        $manager->flush();
    }
}
