<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2015 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SonataAdminIntegrationBundle\Admin\Menu;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\DoctrinePHPCRAdminBundle\Form\Type\TreeManagerType;
use Symfony\Cmf\Bundle\MenuBundle\Doctrine\Phpcr\Menu;
use Symfony\Cmf\Bundle\TreeBrowserBundle\Form\Type\TreeSelectType;

class MenuAdmin extends AbstractMenuNodeAdmin
{
    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);

        $subject = $this->getSubject();
        $isNew = $subject->getId() === null;

        if (!$isNew) {
            $formMapper
                ->tab('form.tab_general')
                    ->with('form.group_items', ['class' => 'col-md-6'])
                        ->add(
                            'children',
                            TreeSelectType::class,
                            ['root_node' => $this->menuRoot],
                            ['help' => 'help.help_items',]
                        )
                    ->end()
                ->end()
            ;

            $this->addTransformerToField($formMapper->getFormBuilder(), 'children');
        }
    }

    public function getNewInstance()
    {
        /** @var $new Menu */
        $new = parent::getNewInstance();
        $new->setParentDocument($this->getModelManager()->find(null, $this->menuRoot));

        return $new;
    }
}
