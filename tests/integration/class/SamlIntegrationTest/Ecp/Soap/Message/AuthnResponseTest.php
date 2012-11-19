<?php

namespace SamlIntegrationTest\Ecp\Soap\Message;

use Saml\Ecp\Soap\Message\AuthnResponse;


class AuthnResponseTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var AuthnResponse
     */
    protected $_message = null;


    public function setUp ()
    {
        $this->_message = new AuthnResponse($this->_getSoapData());
    }


    public function testGetAssertionConsumerServiceUrl ()
    {
        $this->assertSame('https://sp.example.org/Shibboleth.sso/SAML2/ECP', $this->_message->getAssertionConsumerServiceUrl());
    }


    protected function _getSoapData ()
    {
        return file_get_contents(TESTS_FILES_DIR . 'soap/soap-saml-authn-response.xml');
    }
}