<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SonataPhpcrAdminIntegrationBundle\Admin\Block\Extension;

use Sonata\AdminBundle\Admin\AbstractAdminExtension;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Provide cache form fields for block models.
 *
 * @author Sven Cludius <sven.cludius@valiton.com>
 */
class BlockCacheExtension extends AbstractAdminExtension
{
    private $formGroup;
    private $formTab;

    public function __construct($formGroup = 'form.group_metadata', $formTab = 'form.tab_general')
    {
        $this->formGroup = $formGroup;
        $this->formTab = $formTab;
    }

    /**
     * Configure form fields.
     *
     * @param FormMapper $formMapper
     */
    public function configureFormFields(FormMapper $formMapper)
    {
        if ($formMapper->hasOpenTab()) {
            $formMapper->end();
        }

        $formMapper
            ->tab($this->formTab, 'form.tab_general' === $this->formTab
                ? ['translation_domain' => 'CmfSonataPhpcrAdminIntegrationBundle']
                : []
            )
                ->with($this->formGroup, 'form.group_metadata' === $this->formGroup
                    ? ['translation_domain' => 'CmfSonataPhpcrAdminIntegrationBundle']
                    : []
                )
                    ->add('ttl', TextType::class)
                ->end()
            ->end()
        ;
    }
}
