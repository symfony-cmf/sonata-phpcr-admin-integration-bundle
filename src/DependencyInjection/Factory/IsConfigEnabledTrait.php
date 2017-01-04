<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SonataPhpcrAdminIntegrationBundle\DependencyInjection\Factory;

use Symfony\Component\DependencyInjection\ContainerBuilder;

trait IsConfigEnabledTrait
{
    public function isConfigEnabled(ContainerBuilder $container, array $config)
    {
        if (!array_key_exists('enabled', $config)) {
            throw new InvalidArgumentException("The config array has no 'enabled' key.");
        }

        return (bool) $container->getParameterBag()->resolveValue($config['enabled']);
    }
}
