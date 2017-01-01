<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SonataAdminIntegrationBundle\Admin\Content;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\DoctrinePHPCRAdminBundle\Admin\Admin;
use Symfony\Cmf\Bundle\ContentBundle\Doctrine\Phpcr\Form\Type\StaticContentType;
use Symfony\Cmf\Bundle\ContentBundle\Model\StaticContentBase;

class StaticContentAdmin extends Admin
{
    protected $translationDomain = 'CmfSonataAdminIntegrationBundle';

    public function getExportFormats()
    {
        return array();
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id', 'text')
            ->addIdentifier('title', 'text')
        ;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $builder = $formMapper->getFormBuilder()->getFormFactory()->createBuilder(StaticContentType::class, null, [
            'readonly_parent_document' => (bool) $this->id($this->getSubject()),
            'label_format' => 'form.label_%name%',
            'translation_domain' => 'CmfSonataAdminIntegrationBundle',
        ]);

        $formMapper
            ->tab('form.tab_general')
                ->with('form.group_content', ['class' => 'col-md-9'])
                    ->add($builder->get('title'))
                    ->add($builder->get('body'))
                ->end()

                ->with('form.group_location', ['class' => 'col-md-3'])
                    ->add($builder->get('parentDocument'))
                    ->add($builder->get('name'))
                ->end()
            ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title', 'doctrine_phpcr_string')
            ->add('name', 'doctrine_phpcr_nodename')
        ;
    }

    public function toString($object)
    {
        return $object instanceof StaticContentBase && $object->getTitle()
            ? $object->getTitle()
            : $this->trans('link_add', array(), 'SonataAdminBundle')
        ;
    }
}
