<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2015 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SonataAdminIntegrationBundle\Admin\Route\Extension;

use Sonata\AdminBundle\Admin\AdminExtension;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\CoreBundle\Form\Type\CollectionType;

/**
 * Admin extension to add routes tab to content implementing the
 * RouteReferrersInterface.
 *
 * @author David Buchmann <mail@davidbu.ch>
 */
class RouteReferrersExtension extends AdminExtension
{
    /**
     * @var string
     */
    protected $formGroup;

    /**
     * @param string $formGroup group name to use for form mapper
     */
    public function __construct($formGroup = 'form.group_seo')
    {
        $this->formGroup = $formGroup;
    }

    public function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with($this->formGroup, ['translation_domain' => 'CmfRoutingBundle'])
                ->add('routes', CollectionType::class, [], ['edit' => 'inline', 'inline' => 'table'])
            ->end()
        ;
    }
}
