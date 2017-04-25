<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$container->setParameter('cmf_testing.bundle_fqn', 'Symfony\Cmf\Bundle\SonataPhpcrAdminIntegrationBundle');
$loader->import(CMF_TEST_CONFIG_DIR.'/default.php');
$loader->import(__DIR__.'/cmf_sonata_admin_integration.yml');
$loader->import(__DIR__.'/sonata_admin.yml');
$loader->import(CMF_TEST_CONFIG_DIR.'/phpcr_odm.php');
