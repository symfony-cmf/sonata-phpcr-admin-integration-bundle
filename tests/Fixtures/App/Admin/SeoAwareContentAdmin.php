<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SonataPhpcrAdminIntegrationBundle\Tests\Fixtures\App\Admin;

class SeoAwareContentAdmin extends BaseAdmin
{
    protected $baseRouteName = 'cmf_seo_aware_content';

    protected $baseRoutePattern = 'cmf/seo/seoawarecontent';
}
