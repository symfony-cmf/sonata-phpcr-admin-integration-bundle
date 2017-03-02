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
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

trait IsConfigEnabledTrait
{
    /**
     * @param ContainerBuilder $container   The container
     * @param array            $config      The configuration section that could be enabled
     * @param string           $enabledFlag Name of the flag that tells if config is enabled. Defaults to "enabled"
     *
     * @return bool
     */
    public function isConfigEnabled(ContainerBuilder $container, array $config, $enabledFlag = 'enabled')
    {
        if (!array_key_exists($enabledFlag, $config)) {
            throw new InvalidArgumentException("The config array has no 'enabled' key.");
        }

        return (bool) $container->getParameterBag()->resolveValue($config[$enabledFlag]);
    }
}
