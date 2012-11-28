<?php

namespace SamlTest\Ecp\Client\Exception;

use Saml\Ecp\Client\Exception\ResponseValidationException;


class ResponseValidationExceptionTest extends \PHPUnit_Framework_TestCase
{


    public function testCreate ()
    {
        $response = $this->getMock('Saml\Ecp\Response\ResponseInterface');
        $validator = $this->getMock('Saml\Ecp\Response\Validator\ValidatorInterface');
        $previousException = $this->getMock('\Exception');
        
        $e = new ResponseValidationException($response, $validator, $previousException);
        $this->assertSame($response, $e->getResponse());
        $this->assertSame($validator, $e->getValidator());
        $this->assertSame($previousException, $e->getPrevious());
    }
}