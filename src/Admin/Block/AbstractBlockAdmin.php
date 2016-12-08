<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SonataAdminIntegrationBundle\Admin\Block;

use Sonata\DoctrinePHPCRAdminBundle\Admin\Admin;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
abstract class AbstractBlockAdmin extends Admin
{
    /**
     * @var string
     */
    protected $translationDomain = 'CmfBlockBundle';

    /**
     * {@inheritdoc}
     */
    public function getExportFormats()
    {
        return array();
    }
}
