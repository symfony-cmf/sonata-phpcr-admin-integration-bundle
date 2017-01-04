<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SonataPhpcrAdminIntegrationBundle\Admin\Block;

use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Cmf\Bundle\SonataPhpcrAdminIntegrationBundle\Admin\AbstractAdmin;
use Symfony\Cmf\Bundle\TreeBrowserBundle\Form\Type\TreeSelectType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
abstract class AbstractBlockAdmin extends AbstractAdmin
{
    /**
     * @var string
     */
    protected $translationDomain = 'CmfSonataPhpcrAdminIntegrationBundle';

    /**
     * {@inheritdoc}
     */
    public function getExportFormats()
    {
        return [];
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
                        TreeSelectType::class,
                        ['root_node' => $this->getRootPath(), 'widget' => 'browser']
                    )
                    ->add('name', TextType::class)
                ->end()
            ->end()
        ;

        $this->addTransformerToField($formMapper->getFormBuilder(), 'parentDocument');
    }
}
