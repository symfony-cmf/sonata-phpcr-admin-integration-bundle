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

use Burgov\Bundle\KeyValueFormBundle\Form\Type\KeyValueType;
use Sonata\AdminBundle\Admin\AbstractAdminExtension;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Admin extension for editing menu options
 * implementing MenuOptionsInterface.
 *
 * @author Mojtaba Koosej <mkoosej@gmail.com>
 */
class MenuOptionsExtension extends AbstractAdminExtension
{
    /**
     * @var string
     */
    protected $formGroup;

    /**
     * @var string
     */
    protected $formTab;

    /**
     * @var bool
     */
    protected $advanced;

    /**
     * @param string $formGroup - group to use for form mapper
     * @param bool   $advanced  - activates editing all fields of the node
     */
    public function __construct($formGroup = 'form.group_menu_options', $formTab = 'form.tab_general', $advanced = false)
    {
        $this->formGroup = $formGroup;
        $this->formTab = $formTab;
        $this->advanced = $advanced;
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
            ->tab($this->formTab, 'form.tab_general' === $this->formTab
                ? ['translation_domain' => 'CmfSonataPhpcrAdminIntegrationBundle']
                : []
            )
                ->with($this->formGroup, 'form.group_menu_options' === $this->formGroup
                    ? ['class' => 'col-md-3', 'translation_domain' => 'CmfSonataPhpcrAdminIntegrationBundle']
                    : ['class' => 'col-md-3']
                )
                    ->add('display', CheckboxType::class, ['required' => false], ['help' => 'form.help_display'])
                    ->add('displayChildren', CheckboxType::class, ['required' => false], ['help' => 'form.help_display_children'])
        ;

        if (!$this->advanced) {
            $formMapper->end()->end();

            return;
        }

        $child_options = array(
            'value_type' => TextType::class,
            'label' => false,
            'attr' => array('style' => 'clear:both'),
        );

        $formMapper
            ->add(
                'attributes',
                KeyValueType::class,
                [
                  'value_type' => TextType::class,
                  'required' => false,
                  'entry_options' => $child_options,
                ]
            )
            ->add(
                'labelAttributes',
                KeyValueType::class,
                [
                  'value_type' => TextType::class,
                  'required' => false,
                  'entry_options' => $child_options,
                ]
            )
            ->add(
                'childrenAttributes',
                KeyValueType::class,
                [
                  'value_type' => TextType::class,
                  'required' => false,
                  'entry_options' => $child_options,
                ]
            )
            ->add(
                'linkAttributes',
                KeyValueType::class,
                [
                  'value_type' => TextType::class,
                  'required' => false,
                  'entry_options' => $child_options,
                ]
            )
            ->end() // group
            ->end(); // tab
    }
}
