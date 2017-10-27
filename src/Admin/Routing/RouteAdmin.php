<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SonataPhpcrAdminIntegrationBundle\Admin\Routing;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\CoreBundle\Form\Type\ImmutableArrayType;
use Symfony\Cmf\Bundle\RoutingBundle\Form\Type\RouteTypeType;
use Symfony\Cmf\Bundle\RoutingBundle\Model\Route;
use Symfony\Cmf\Bundle\SonataPhpcrAdminIntegrationBundle\Admin\AbstractAdmin;
use Symfony\Cmf\Bundle\TreeBrowserBundle\Form\Type\TreeSelectType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RouteAdmin extends AbstractAdmin
{
    protected $translationDomain = 'CmfSonataPhpcrAdminIntegrationBundle';

    /**
     * Root path for the route content selection.
     *
     * @var string
     */
    protected $contentRoot;

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('path', 'text');
    }

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
                ->end() // group location
        ;

        if (null === $this->getParentFieldDescription()) {
            $formMapper
                ->with('form.group_target', ['class' => 'col-md-9'])
                    ->add(
                        'content',
                        TreeSelectType::class,
                        ['root_node' => $this->contentRoot, 'widget' => 'browser', 'required' => false]
                    )
                ->end() // group general
            ->end() // tab general

            ->tab('form.tab_routes')
                ->with('form.group_path', ['class' => 'col-md-6'])
                    ->add(
                        'variablePattern',
                        TextType::class,
                        ['required' => false],
                        ['help' => 'form.help_variable_pattern']
                    )
                    ->add(
                        'options',
                        ImmutableArrayType::class,
                        ['keys' => $this->configureFieldsForOptions($this->getSubject()->getOptions())],
                        ['help' => 'form.help_options']
                    )
                ->end() // group path

                ->with('form.group_defaults', ['class' => 'col-md-6'])
                    ->add(
                        'defaults',
                        ImmutableArrayType::class,
                        ['label' => false, 'keys' => $this->configureFieldsForDefaults($this->getSubject()->getDefaults())]
                    )
                ->end() // group data
            ;
        }

        $formMapper
            ->end(); // tab general/routing

        $this->addTransformerToField($formMapper->getFormBuilder(), 'parentDocument');
        if (null === $this->getParentFieldDescription()) {
            $this->addTransformerToField($formMapper->getFormBuilder(), 'content');
        }
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('name', 'doctrine_phpcr_nodename');
    }

    public function setContentRoot($contentRoot)
    {
        $this->contentRoot = $contentRoot;
    }

    public function getExportFormats()
    {
        return [];
    }

    /**
     * Provide default route defaults and extract defaults from $dynamicDefaults.
     *
     * @param array $dynamicDefaults
     *
     * @return array Value for sonata_type_immutable_array
     */
    protected function configureFieldsForDefaults($dynamicDefaults)
    {
        $defaults = [
            '_controller' => ['_controller', TextType::class, ['required' => false, 'translation_domain' => 'CmfSonataPhpcrAdminIntegrationBundle']],
            '_template' => ['_template', TextType::class, ['required' => false, 'translation_domain' => 'CmfSonataPhpcrAdminIntegrationBundle']],
            'type' => ['type', RouteTypeType::class, [
                'placeholder' => '',
                'required' => false,
                'translation_domain' => 'CmfSonataPhpcrAdminIntegrationBundle',
            ]],
        ];

        foreach ($dynamicDefaults as $name => $value) {
            if (!isset($defaults[$name])) {
                $defaults[$name] = [$name, TextType::class, ['required' => false]];
            }
        }

        //parse variable pattern and add defaults for tokens - taken from routecompiler
        /** @var $route Route */
        $route = $this->subject;
        if ($route && $route->getVariablePattern()) {
            preg_match_all('#\{\w+\}#', $route->getVariablePattern(), $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
            foreach ($matches as $match) {
                $name = substr($match[0][0], 1, -1);
                if (!isset($defaults[$name])) {
                    $defaults[$name] = [$name, TextType::class, ['required' => true]];
                }
            }
        }

        if ($route && $route->getOption('add_format_pattern')) {
            $defaults['_format'] = ['_format', TextType::class, ['required' => true]];
        }
        if ($route && $route->getOption('add_locale_pattern')) {
            $defaults['_locale'] = ['_locale', TextType::class, ['required' => false]];
        }

        return $defaults;
    }

    /**
     * Provide default options and extract options from $dynamicOptions.
     *
     * @param array $dynamicOptions
     *
     * @return array Value for sonata_type_immutable_array
     */
    protected function configureFieldsForOptions(array $dynamicOptions)
    {
        $options = [
            'add_locale_pattern' => ['add_locale_pattern', CheckboxType::class, ['required' => false, 'label' => 'form.label_add_locale_pattern', 'translation_domain' => 'CmfSonataPhpcrAdminIntegrationBundle']],
            'add_format_pattern' => ['add_format_pattern', CheckboxType::class, ['required' => false, 'label' => 'form.label_add_format_pattern', 'translation_domain' => 'CmfSonataPhpcrAdminIntegrationBundle']],
            'add_trailing_slash' => ['add_trailing_slash', CheckboxType::class, ['required' => false, 'label' => 'form.label_add_trailing_slash', 'translation_domain' => 'CmfSonataPhpcrAdminIntegrationBundle']],
        ];

        foreach ($dynamicOptions as $name => $value) {
            if (!isset($options[$name])) {
                $options[$name] = [$name, TextType::class, ['required' => false]];
            }
        }

        return $options;
    }

    public function prePersist($object)
    {
        $defaults = array_filter($object->getDefaults());
        $object->setDefaults($defaults);
    }

    public function preUpdate($object)
    {
        $defaults = array_filter($object->getDefaults());
        $object->setDefaults($defaults);
    }

    public function toString($object)
    {
        return $object instanceof Route && $object->getId()
            ? $object->getId()
            : $this->trans('link_add', [], 'SonataAdminBundle')
        ;
    }
}
