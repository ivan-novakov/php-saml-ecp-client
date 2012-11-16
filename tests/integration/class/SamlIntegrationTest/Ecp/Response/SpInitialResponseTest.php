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


    public function testGetResponseConsumerUrl ()
    {
        $this->assertSame('https://sp.example.org/Shibboleth.sso/SAML2/ECP', $this->_response->getResponseConsumerUrl());
    }
}