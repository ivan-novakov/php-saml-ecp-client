<?php

namespace SamlTest\Ecp\Response;

use Saml\Ecp\Response\SpInitialResponse;


class SpInitialResponseTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var SpInitialResponse
     */
    protected $_response = null;


    public function setUp ()
    {
        $this->_response = new SpInitialResponse(new \Zend\Http\Response());
    }


    public function testValidateBadStatus ()
    {
        $this->setExpectedException('Saml\Ecp\Response\Exception\BadResponseStatusException');
        
        $httpResponse = new \Zend\Http\Response();
        $httpResponse->setStatusCode(400);
        $this->_response->setHttpResponse($httpResponse);
        
        $this->_response->validate();
    }


    public function testValidateInvalidContentType ()
    {
        $this->setExpectedException('Saml\Ecp\Response\Exception\InvalidContentTypeException');
        $this->_response->validate();
    }


    public function testValidateMissingSoapMessage ()
    {
        $this->setExpectedException('Saml\Ecp\Response\Exception\MissingSoapMessageException');
        
        $httpResponse = new \Zend\Http\Response();
        $httpResponse->getHeaders()
            ->addHeaders(array(
            'Content-Type' => 'application/vnd.paos+xml'
        ));
        $this->_response->setHttpResponse($httpResponse);
        
        $this->_response->validate();
    }
}