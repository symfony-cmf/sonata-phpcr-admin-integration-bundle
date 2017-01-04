<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SonataPhpcrAdminIntegrationBundle\Admin\Menu;

use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Cmf\Bundle\SonataPhpcrAdminIntegrationBundle\Admin\AbstractAdmin;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Cmf\Bundle\MenuBundle\Model\MenuNodeBase;

/**
 * Common base admin for Menu and MenuNode.
 */
abstract class AbstractMenuNodeAdmin extends AbstractAdmin
{
    /**
     * @var string
     */
    protected $contentRoot;

    /**
     * @var string
     */
    protected $menuRoot;

    /**
     * @var string
     */
    protected $translationDomain = 'CmfSonataPhpcrAdminIntegrationBundle';

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name', 'text')
            ->add('label', 'text')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->tab('form.tab_general')
                ->with('form.group_location', ['class' => 'col-md-3'])
                    ->add('name', TextType::class)
                    ->add('label', TextType::class)
                ->end()
            ->end()
        ;
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('name')
            ->add('label')
            ->add('uri')
            ->add('content', null, array('associated_property' => 'title'))
        ;
    }

    public function getExportFormats()
    {
        return [];
    }

    public function setContentRoot($contentRoot)
    {
        $this->contentRoot = $contentRoot;
    }

    public function setMenuRoot($menuRoot)
    {
        $this->menuRoot = $menuRoot;
    }

    public function setContentTreeBlock($contentTreeBlock)
    {
        $this->contentTreeBlock = $contentTreeBlock;
    }

    public function toString($object)
    {
        if ($object instanceof MenuNodeBase && $object->getLabel()) {
            return $object->getLabel();
        }

        return $this->trans('link_add', array(), 'SonataAdminBundle');
    }
}
