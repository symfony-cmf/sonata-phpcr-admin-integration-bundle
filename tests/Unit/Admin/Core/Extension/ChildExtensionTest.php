<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2017 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\SonataPhpcrAdminIntegrationBundle\Tests\Unit\Admin\Core\Extension;

use Symfony\Cmf\Bundle\SonataPhpcrAdminIntegrationBundle\Admin\Core\Extension\ChildExtension;

class ChildExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testAlterNewInstance()
    {
        $parent = new \StdClass();

        $modelManager = $this->createMock('Sonata\AdminBundle\Model\ModelManagerInterface');
        $modelManager->expects($this->any())
            ->method('find')
            ->will($this->returnValue($parent))
        ;

        $request = $this->createMock('Symfony\Component\HttpFoundation\Request');
        $request->expects($this->any())
            ->method('get')
            ->will($this->returnValue('parent-id'))
        ;

        $admin = $this->createMock('Sonata\AdminBundle\Admin\AdminInterface');
        $admin->expects($this->any())
            ->method('getModelManager')
            ->will($this->returnValue($modelManager))
        ;
        $admin->expects($this->any())
            ->method('hasRequest')
            ->will($this->returnValue(true))
        ;
        $admin->expects($this->any())
            ->method('getRequest')
            ->will($this->returnValue($request))
        ;

        $child = $this->createMock('Symfony\Cmf\Bundle\CoreBundle\Model\ChildInterface');
        $child->expects($this->once())
            ->method('setParentObject')
            ->with($this->equalTo($parent));

        $extension = new ChildExtension();
        $extension->alterNewInstance($admin, $child);
    }
}
