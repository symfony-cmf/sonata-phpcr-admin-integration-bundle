<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SonataPhpcrAdminIntegrationBundle\Admin;

use Doctrine\Bundle\PHPCRBundle\Form\DataTransformer\DocumentToPathTransformer;
use Sonata\DoctrinePHPCRAdminBundle\Admin\Admin;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\Form\FormBuilder;

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@mayflower.de>
 */
class AbstractAdmin extends Admin
{
    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;

    public function setManagerRegistry(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * Will add a the phpcr data transformer to a specific field.
     *
     * @param FormBuilder $formBuilder
     * @param $fieldName
     */
    public function addTransformerToField(FormBuilder $formBuilder, $fieldName)
    {
        $formBuilder->get($fieldName)->addModelTransformer(new DocumentToPathTransformer(
            $this->managerRegistry->getManagerForClass($this->getClass())
        ));
    }
}
