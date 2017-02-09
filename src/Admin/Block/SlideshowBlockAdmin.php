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
use Sonata\AdminBundle\Datagrid\ListMapper;
use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\SlideshowBlock;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sonata\CoreBundle\Form\Type\CollectionType;

/**
 * @author Horner
 */
class SlideshowBlockAdmin extends AbstractBlockAdmin
{
    /**
     * Service name of the sonata_type_collection service to embed.
     *
     * @var string
     */
    protected $embeddedAdminCode;

    /**
     * Configure the service name (admin_code) of the admin service for the embedded slides.
     *
     * @param string $adminCode
     */
    public function setEmbeddedSlidesAdmin($adminCode)
    {
        $this->embeddedAdminCode = $adminCode;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        parent::configureListFields($listMapper);
        $listMapper
            ->addIdentifier('id', 'text')
            ->add('title', 'text')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        if (null === $this->getParentFieldDescription()) {
            parent::configureFormFields($formMapper);
        }

        $formMapper
            ->tab('form.tab_general')
                ->with('form.group_block', null === $this->getParentFieldDescription()
                    ? ['class' => 'col-md-9']
                    : []
                )
                    ->add('title', TextType::class, array('required' => false))
                    ->add('children', CollectionType::class,
                        array(),
                        array(
                            'edit' => 'inline',
                            'inline' => 'table',
                            'admin_code' => $this->embeddedAdminCode,
                            'sortable' => 'position',
                        ))
                ->end()
            ->end()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function prePersist($slideshow)
    {
        foreach ($slideshow->getChildren() as $child) {
            $child->setName($this->generateName());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate($slideshow)
    {
        foreach ($slideshow->getChildren() as $child) {
            if (!$this->modelManager->getNormalizedIdentifier($child)) {
                $child->setName($this->generateName());
            }
        }
    }

    /**
     * Generate a most likely unique name.
     *
     * TODO: have blocks use the autoname annotation - https://github.com/symfony-cmf/BlockBundle/issues/149
     *
     * @return string
     */
    private function generateName()
    {
        return 'child_'.time().'_'.rand();
    }

    public function toString($object)
    {
        return $object instanceof SlideshowBlock && $object->getTitle()
            ? $object->getTitle()
            : parent::toString($object)
        ;
    }
}
