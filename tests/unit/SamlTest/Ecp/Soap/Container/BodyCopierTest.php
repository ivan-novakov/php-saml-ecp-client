<?php

namespace SamlTest\Ecp\Soap\Container;

use Saml\Ecp\Soap\Container\BodyCopier;


class BodyCopierTest extends \PHPUnit_Framework_TestCase
{


    public function testCopyBody ()
    {
        $fromSoap = $this->getMockBuilder('Saml\Ecp\Soap\Message\Message')
            ->disableOriginalConstructor()
            ->getMock();
        
        $fromContainer = $this->getMock('Saml\Ecp\Soap\Container\ContainerInterface');
        $fromContainer->expects($this->once())
            ->method('getSoapMessage')
            ->will($this->returnValue($fromSoap));
        
        $toSoap = $this->getMockBuilder('Saml\Ecp\Soap\Message\Message')
            ->disableOriginalConstructor()
            ->getMock();
        $toSoap->expects($this->once())
            ->method('copyBodyFromMessage')
            ->with($fromSoap);
        
        $toContainer = $this->getMock('Saml\Ecp\Soap\Container\ContainerInterface');
        $toContainer->expects($this->once())
            ->method('getSoapMessage')
            ->will($this->returnValue($toSoap));
        $toContainer->expects($this->once())
            ->method('setSoapMessage')
            ->with($toSoap);
        
        $bodyCopier = new BodyCopier();
        $bodyCopier->copyBody($fromContainer, $toContainer);
    }
}