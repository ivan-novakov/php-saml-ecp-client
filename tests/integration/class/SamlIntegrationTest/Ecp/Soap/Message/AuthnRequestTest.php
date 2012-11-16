<?php

namespace SamlIntegrationTest\Ecp\Soap\Message;

use Saml\Ecp\Soap\Message\AuthnRequest;


class AuthnRequestTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var AuthnRequest
     */
    protected $_message = null;


    public function setUp ()
    {
        $this->_message = new AuthnRequest($this->_getSoapData());
    }


    public function testGetPaosRequestService ()
    {
        $this->assertSame('urn:oasis:names:tc:SAML:2.0:profiles:SSO:ecp', $this->_message->getPaosRequestService());
    }


    public function testGetPaosResponseConsumerUrl ()
    {
        $this->assertSame('https://sp.example.org/Shibboleth.sso/SAML2/ECP', $this->_message->getPaosResponseConsumerUrl());
    }


    public function testGetAssertionConsumerServiceUrl ()
    {
        $this->assertSame('https://sp.example.org/Shibboleth.sso/SAML2/ECP', $this->_message->getAssertionConsumerServiceUrl());
    }


    public function testGetSpName ()
    {
        $this->assertSame('https://sp.example.org/shibboleth', $this->_message->getSpName());
    }


    public function testGetIdpList ()
    {
        $expected = array(
            'https://idp.example.org/idp/shibboleth'
        );
        $this->assertSame($expected, $this->_message->getIdPList());
    }


    public function testGetRelayState ()
    {
        $this->assertSame('ss:mem:cca27ceabb8b0035ead1d99c0e343fa9234d6d559d5da43d4efb9d6cb592c0cf', $this->_message->getRelayState());
    }


    protected function _getSoapData ()
    {
        return file_get_contents(TESTS_FILES_DIR . 'soap/soap-saml-authn-request.xml');
    }
}