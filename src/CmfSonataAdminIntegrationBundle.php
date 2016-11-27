<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SonataAdminIntegrationBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Cmf\Bundle\SonataAdminIntegrationBundle\DependencyInjection\Factory;

class CmfSonataAdminIntegrationBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $extension = $container->getExtension('cmf_sonata_admin_integration');
        $extension->registerAdminFactory(new Factory\SeoAdminFactory());
    }
}
