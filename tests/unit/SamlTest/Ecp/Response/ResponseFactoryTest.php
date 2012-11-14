<?php

namespace SamlTest\Ecp\Response;

use Saml\Ecp\Response\ResponseFactory;


class ResponseFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var ResponseFactory
     */
    protected $_factory = null;


    public function setUp ()
    {
        $this->_factory = new ResponseFactory();
    }


    public function testCreateSpInitialResponse ()
    {
        $this->assertInstanceOf('Saml\Ecp\Response\SpInitialResponse', $this->_factory->createSpInitialResponse($this->_getHttpResponseMock()));
    }


    public function testCreateIdpAuthnResponse ()
    {
        $this->assertInstanceOf('Saml\Ecp\Response\IdpAuthnResponse', $this->_factory->createIdpAuthnResponse($this->_getHttpResponseMock()));
    }


    public function testCreateSpConveryAuthnResponse ()
    {
        $this->assertInstanceOf('Saml\Ecp\Response\SpConveyAuthnResponse', $this->_factory->createSpConveryAuthnResponse($this->_getHttpResponseMock()));
    }


    public function testCreateSpResourceResponse ()
    {
        $this->assertInstanceOf('Saml\Ecp\Response\SpResourceResponse', $this->_factory->createSpResourceResponse($this->_getHttpResponseMock()));
    }


    protected function _getHttpResponseMock ()
    {
        return $this->getMock('Zend\Http\Response');
    }
}