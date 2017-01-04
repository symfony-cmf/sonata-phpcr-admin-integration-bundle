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

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Cmf\Bundle\TreeBrowserBundle\Form\Type\TreeSelectType;

/**
 * Sonata admin for the MenuBlock. Allows to select the target menu node from
 * an odm tree at the menu root.
 *
 * @author Philipp A. Mohrenweiser <phiamo@googlemail.com>
 */
class MenuBlockAdmin extends AbstractBlockAdmin
{
    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id', 'text')
            ->add('name', 'text')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);

        $formMapper
            ->tab('form.tab_general')
                ->with('form.group_block', ['class' => 'col-md-9'])
                    ->add(
                        'menuNode',
                        TreeSelectType::class,
                        ['root_node' => $this->menuPath, 'widget' => 'browser', 'required' => true]
                    )
                ->end()
            ->end()
        ;

        $this->addTransformerToField($formMapper->getFormBuilder(), 'menuNode');
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name', 'doctrine_phpcr_nodename')
        ;
    }

    /**
     * PHPCR to the root of all menu nodes for the selection of the target.
     *
     * @var string
     */
    private $menuPath;

    /**
     * Set the menu root for selection of the target of this block.
     *
     * @param string $menuPath
     */
    public function setMenuPath($menuPath)
    {
        $this->menuPath = $menuPath;
    }

    /**
     * @return string
     */
    public function getMenuPath()
    {
        return $this->menuPath;
    }
}
