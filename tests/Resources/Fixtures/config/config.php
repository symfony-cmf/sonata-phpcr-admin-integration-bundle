<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$container->loadFromExtension('cmf_sonata_phpcr_admin_integration', [
    'bundles' => [
        'seo' => [
            'enabled' => true,
        ],
        'core' => [
            'enabled' => true,
        ],
    ],
]);
