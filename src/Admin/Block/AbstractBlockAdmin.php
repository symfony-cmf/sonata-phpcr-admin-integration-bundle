<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SonataAdminIntegrationBundle\Admin\Block;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\DoctrinePHPCRAdminBundle\Admin\Admin;
use Sonata\DoctrinePHPCRAdminBundle\Form\Type\TreeModelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
abstract class AbstractBlockAdmin extends Admin
{
    /**
     * @var string
     */
    protected $translationDomain = 'CmfSonataAdminIntegrationBundle';

    /**
     * {@inheritdoc}
     */
    public function getExportFormats()
    {
        return array();
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->tab('form.tab_general')
                ->with('form.group_location', ['class' => 'col-md-3'])
                    ->add(
                        'parentDocument',
                        TreeModelType::class,
                        ['root_node' => $this->getRootPath(), 'choice_list' => array(), 'select_root_node' => true]
                    )
                    ->add('name', TextType::class)
                ->end()
            ->end()
        ;
    }
}
