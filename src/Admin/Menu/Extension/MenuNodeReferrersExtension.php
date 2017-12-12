<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SonataPhpcrAdminIntegrationBundle\Admin\Menu\Extension;

use Sonata\AdminBundle\Admin\AbstractAdminExtension;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\CoreBundle\Form\Type\CollectionType;

/**
 * Admin extension to add menu items tab to content.
 *
 * @author David Buchmann <mail@davidbu.ch>
 */
class MenuNodeReferrersExtension extends AbstractAdminExtension
{
    private $formGroup;

    private $formTab;

    public function __construct($formGroup = 'form.group_menus', $formTab = 'form.tab_menu')
    {
        $this->formGroup = $formGroup;
        $this->formTab = $formTab;
    }

    public function configureFormFields(FormMapper $formMapper)
    {
        if ($formMapper->hasOpenTab()) {
            $formMapper->end();
        }

        $formMapper
            ->tab($this->formTab, 'form.tab_menu' === $this->formTab
                ? ['translation_domain' => 'CmfSonataPhpcrAdminIntegrationBundle']
                : []
            )
                ->with($this->formGroup, 'form.group_menus' === $this->formGroup
                    ? ['translation_domain' => 'CmfSonataPhpcrAdminIntegrationBundle']
                    : []
                )
                    ->add(
                        'menuNodes',
                        CollectionType::class,
                        [],
                        ['edit' => 'inline', 'inline' => 'table']
                    )
                ->end()
            ->end()
        ;
    }
}
