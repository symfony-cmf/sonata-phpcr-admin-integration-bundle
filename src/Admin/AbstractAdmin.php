<?php

namespace Symfony\Cmf\Bundle\SonataAdminIntegrationBundle\Admin;

use Doctrine\Bundle\PHPCRBundle\Form\DataTransformer\DocumentToPathTransformer;
use Sonata\DoctrinePHPCRAdminBundle\Admin\Admin;

/**
 * @author Maximilian Berghoff <Maximilian.Berghoff@mayflower.de>
 */
class AbstractAdmin extends Admin
{
    /**
     * Will add a the phpcr data transformer to a specific field.
     *
     * @param $fieldName
     */
    public function addTransformerToField($fieldName)
    {
        $this->getFormBuilder()
            ->get($fieldName)
            ->addModelTransformer(new DocumentToPathTransformer($this->getModelManager()))
        ;
    }
}
