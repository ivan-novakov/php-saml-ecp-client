<?php

namespace SamlIntegrationTest\Ecp\Response;

use Saml\Ecp\Response\IdpAuthnResponse;


class IdpAuthnResponseTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var IdpAuthnResponse
     */
    protected $_response = null;


    public function setUp ()
    {
        $httpResponse = \Zend\Http\Response::fromString(file_get_contents(TESTS_FILES_DIR . 'http/idp_authn_response.txt'));
        $this->_response = new IdpAuthnResponse($httpResponse);
    }


    public function testGetAssertionConsumerServiceUrl ()
    {
        $this->assertSame('https://sp.example.org/Shibboleth.sso/SAML2/ECP', $this->_response->getConsumerEndpointUrl());
    }
}