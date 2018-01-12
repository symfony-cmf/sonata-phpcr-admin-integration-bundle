<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Knp\Bundle\MenuBundle\KnpMenuBundle;
use Sonata\AdminBundle\SonataAdminBundle;
use Sonata\BlockBundle\SonataBlockBundle;
use Sonata\CoreBundle\SonataCoreBundle;
use Sonata\DoctrinePHPCRAdminBundle\SonataDoctrinePHPCRAdminBundle;
use Symfony\Cmf\Bundle\TreeBrowserBundle\CmfTreeBrowserBundle;

return [
    Sonata\SeoBundle\SonataSeoBundle::class => ['all' => true],
    Burgov\Bundle\KeyValueFormBundle\BurgovKeyValueFormBundle::class => ['all' => true],
    Symfony\Cmf\Bundle\SonataPhpcrAdminIntegrationBundle\CmfSonataPhpcrAdminIntegrationBundle::class => ['all' => true],
    Symfony\Cmf\Bundle\SeoBundle\CmfSeoBundle::class => ['all' => true],
    Symfony\Cmf\Bundle\RoutingBundle\CmfRoutingBundle::class => ['all' => true],
    Symfony\Cmf\Bundle\CoreBundle\CmfCoreBundle::class => ['all' => true],
    Symfony\Cmf\Bundle\BlockBundle\CmfBlockBundle::class => ['all' => true],
    Symfony\Cmf\Bundle\MenuBundle\CmfMenuBundle::class => ['all' => true],
    Symfony\Cmf\Bundle\ContentBundle\CmfContentBundle::class => ['all' => true],
    JMS\SerializerBundle\JMSSerializerBundle::class => ['all' => true],
    Ivory\CKEditorBundle\IvoryCKEditorBundle::class => ['all' => true],
    SonataBlockBundle::class => ['all' => true],
    SonataCoreBundle::class => ['all' => true],
    SonataAdminBundle::class => ['all' => true],
    KnpMenuBundle::class => ['all' => true],
    SonataDoctrinePHPCRAdminBundle::class => ['all' => true],
    CmfTreeBrowserBundle::class => ['all' => true],
];
