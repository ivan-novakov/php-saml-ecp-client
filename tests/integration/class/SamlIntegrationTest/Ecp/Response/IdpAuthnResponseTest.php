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
        $httpResponse = \Zend\Http\Response::fromString(file_get_contents(TESTS_FILES_DIR . 'http/sp_initial_response.txt'));
        $this->_response = new IdpAuthnResponse($httpResponse);
    }
    
}