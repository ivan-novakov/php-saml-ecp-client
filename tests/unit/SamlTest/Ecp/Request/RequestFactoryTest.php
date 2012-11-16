<?php

namespace SamlTest\Ecp\Request;

use Saml\Ecp\Request\RequestFactory;


class RequestFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var RequestFactory
     */
    protected $_factory = null;


    public function setUp ()
    {
        $this->_factory = new RequestFactory();
    }


    public function testCreateSpInitialRequest ()
    {
        $request = $this->_factory->createSpInitialRequest('http://test/');
        $this->assertInstanceOf('Saml\Ecp\Request\SpInitialRequest', $request);
        $this->assertSame('http://test/', $request->getUri());
    }


    public function testCreateIdpAuthnRequest ()
    {
        $soapContainer = $this->_getSoapContainerMock();
        
        $bodyCopier = $this->_getBodyCopierMock($soapContainer);
        $this->_factory->setSoapBodyCopier($bodyCopier);
        
        $request = $this->_factory->createIdpAuthnRequest($soapContainer, 'http://test/');
        $this->assertInstanceOf('Saml\Ecp\Request\IdpAuthnRequest', $request);
        $this->assertSame('http://test/', $request->getUri());
    }


    public function testCreateSpConveyAuthnRequest ()
    {
        $soapContainer = $this->_getSoapContainerMock();
        
        $bodyCopier = $this->_getBodyCopierMock($soapContainer);
        $this->_factory->setSoapBodyCopier($bodyCopier);
        
        $request = $this->_factory->createSpAuthnConveyRequest($soapContainer, 'http://test/');
        $this->assertInstanceOf('Saml\Ecp\Request\SpConveyAuthnRequest', $request);
        $this->assertSame('http://test/', $request->getUri());
    }


    public function testCreateSpResourceRequest ()
    {
        $request = $this->_factory->createSpResourceRequest('http://test/');
        $this->assertInstanceOf('Saml\Ecp\Request\SpResourceRequest', $request);
        $this->assertSame('http://test/', $request->getUri());
    }


    protected function _getSoapData ()
    {
        return file_get_contents(TESTS_FILES_DIR . 'soap-saml-authn-request.xml');
    }


    protected function _getBodyCopierMock ($soapContainer)
    {
        $bodyCopier = $this->getMock('Saml\Ecp\Soap\Container\BodyCopier');
        $bodyCopier->expects($this->once())
            ->method('copyBody')
            ->with($soapContainer, $this->isInstanceOf('Saml\Ecp\Request\RequestInterface'));
        
        return $bodyCopier;
    }


    protected function _getSoapContainerMock ()
    {
        return $this->getMock('Saml\Ecp\Soap\Container\ContainerInterface');
    }
}