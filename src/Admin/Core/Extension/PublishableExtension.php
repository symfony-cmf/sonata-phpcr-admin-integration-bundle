<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SonataPhpcrAdminIntegrationBundle\Admin\Core\Extension;

use Sonata\AdminBundle\Admin\AbstractAdminExtension;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

/**
 * Admin extension to add a publish workflow publishable field for models
 * implementing PublishableInterface.
 *
 * @author Daniel Leech <daniel@dantleech.com>
 */
class PublishableExtension extends AbstractAdminExtension
{
    /**
     * @var string
     */
    protected $formGroup;

    protected $formTab;

    /**
     * @param string $formGroup The group to use for form mapper
     * @param string $formTab   The tab to use for form mapper
     */
    public function __construct($formGroup = 'form.group_publish_workflow', $formTab = 'form.tab_publish')
    {
        $this->formGroup = $formGroup;
        $this->formTab = $formTab;
    }

    /**
     * {@inheritdoc}
     */
    public function configureFormFields(FormMapper $formMapper)
    {
        if ($formMapper->hasOpenTab()) {
            $formMapper->end();
        }

        $formMapper
            ->tab($this->formTab, 'form.tab_publish' === $this->formTab
                ? ['translation_domain' => 'CmfSonataPhpcrAdminIntegrationBundle']
                : []
            )
                ->with($this->formGroup, 'form.group_publish_workflow' === $this->formGroup
                    ? ['translation_domain' => 'CmfSonataPhpcrAdminIntegrationBundle']
                    : []
                )
                    ->add('publishable', CheckboxType::class, ['required' => false, 'translation_domain' => 'CmfSonataPhpcrAdminIntegrationBundle'], [
                        'translation_domain' => 'CmfSonataPhpcrAdminIntegrationBundle',
                        'help' => 'form.help_publishable',
                    ])
                ->end()
            ->end();
    }
}
