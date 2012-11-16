<?php

namespace SamlIntegrationTest\Ecp\Response;

use Saml\Ecp\Response\SpInitialResponse;


class SpInitialResponseTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var SpInitialResponse
     */
    protected $_response = null;


    public function setUp ()
    {
        $httpResponse = \Zend\Http\Response::fromString(file_get_contents(TESTS_FILES_DIR . 'http/sp_initial_response.txt'));
        $this->_response = new SpInitialResponse($httpResponse);
    }


    public function testGetPaosRequestService ()
    {
        $this->assertSame('urn:oasis:names:tc:SAML:2.0:profiles:SSO:ecp', $this->_response->getPaosRequestService());
    }


    public function testGetPaosResponseConsumerUrl ()
    {
        $this->assertSame('https://sp.example.org/Shibboleth.sso/SAML2/ECP', $this->_response->getPaosResponseConsumerUrl());
    }


    public function testGetAssertionConsumerServiceUrl ()
    {
        $this->assertSame('https://sp.example.org/Shibboleth.sso/SAML2/ECP', $this->_response->getAssertionConsumerServiceUrl());
    }


    public function testGetSpName ()
    {
        $this->assertSame('https://sp.example.org/shibboleth', $this->_response->getSpName());
    }


    public function testGetIdpList ()
    {
        $expected = array(
            'https://idp.example.org/idp/shibboleth'
        );
        $this->assertSame($expected, $this->_response->getIdPList());
    }


    public function testGetRelayState ()
    {
        $this->assertSame('ss:mem:efcb6c07a2a8e492fb96efed376f0da81ec1d5401f5f6d49ecaa366de5fc33bd', $this->_response->getRelayState());
    }
}