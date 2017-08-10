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
    public function isConfigEnabled(ContainerBuilder $container, array $config)
    {
        if (!array_key_exists('enabled', $config)) {
            throw new InvalidArgumentException("The config array has no 'enabled' key.");
        }

        return (bool) $container->getParameterBag()->resolveValue($config['enabled']);
    }

    public function isConfigEnabledAuto(ContainerBuilder $container, $enabled, $requiredBundle, $message = null)
    {
        $enabled = $container->getParameterBag()->resolveValue($enabled);
        $bundleExists = array_key_exists($requiredBundle, $container->getParameter('kernel.bundles'));

        if ('auto' === $enabled) {
            $enabled = $bundleExists;
        } elseif (true === $enabled && !$bundleExists) {
            if (null === $message) {
                $message = $requiredBundle.' integration was explicitely enabled, but the bundle is not available.';
            }

            throw new \LogicException($message);
        }

        return $enabled;
    }
}
