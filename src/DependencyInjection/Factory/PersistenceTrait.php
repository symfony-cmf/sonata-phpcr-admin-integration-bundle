<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SonataAdminIntegrationBundle\DependencyInjection\Factory;

use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\DependencyInjection\ContainerBuilder;

trait PersistenceTrait
{
    use IsConfigEnabledTrait;

    protected function addPersistenceNode($persistence, NodeBuilder $builder)
    {
        return $builder
                    ->arrayNode($persistence)
                        ->treatFalseLike(['enabled' => false])
                        ->treatTrueLike(['enabled' => true])
                        ->treatNullLike(['enabled' => true])
                        ->beforeNormalization()
                            ->ifArray()
                            ->then(function ($v) {
                                $v['enabled'] = isset($v['enabled']) ? $v['enabled'] : true;

                                return $v;
                            })
                        ->end()
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->enumNode('enabled')
                                ->values([null, true, false])
                                ->defaultNull()
                            ->end()
        ;
    }

    protected function useGlobalIfImplicit($global, $config)
    {
        if (null === $global || !isset($config[$global])) {
            return $config;
        }

        if (null === $config[$global]['enabled']) {
            $config[$global]['enabled'] = true;
        }

        return $config;
    }
}
