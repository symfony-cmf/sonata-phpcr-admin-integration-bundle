<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2015 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SonataAdminIntegrationBundle\Admin\Routing;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Cmf\Bundle\SonataAdminIntegrationBundle\Admin\AbstractAdmin;
use Symfony\Cmf\Bundle\TreeBrowserBundle\Form\Type\TreeSelectType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Cmf\Bundle\RoutingBundle\Model\Route;

class RedirectRouteAdmin extends AbstractAdmin
{
    protected $translationDomain = 'CmfSonataAdminIntegrationBundle';

    /**
     * Root path for the route parent selection.
     *
     * @var string
     */
    protected $routeRoot;

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('path', 'text');
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('form.group_location', ['class' => 'col-md-3'])
                ->add(
                    'parentDocument',
                    TreeSelectType::class,
                    ['root_node' => $this->routeRoot, 'widget' => 'browser']
                )
                ->add('name', TextType::class)
            ->end()

            ->with('form.group_target', ['class' => 'col-md-9'])
                ->add('routeName', TextType::class, ['required' => false])
                ->add('uri', TextType::class, ['required' => false])
                ->add(
                    'routeTarget',
                    TreeSelectType::class,
                    ['root_node' => $this->routeRoot, 'widget' => 'browser', 'required' => false]
                )
            ->end()
        ;
        $this->addTransformerToField($formMapper->getFormBuilder(), 'parentDocument');
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('name', 'doctrine_phpcr_nodename');
    }

    public function setRouteRoot($routeRoot)
    {
        $this->routeRoot = $routeRoot;
    }

    public function getExportFormats()
    {
        return array();
    }

    public function toString($object)
    {
        return $object instanceof Route && $object->getId()
            ? $object->getId()
            : $this->trans('link_add', array(), 'SonataAdminBundle')
        ;
    }
}
