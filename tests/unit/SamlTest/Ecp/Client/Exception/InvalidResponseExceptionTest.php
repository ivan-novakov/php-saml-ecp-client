<?php

namespace SamlTest\Ecp\Client\Exception;

use Saml\Ecp\Client\Exception\InvalidResponseException;


class InvalidResponseExceptionTest extends \PHPUnit_Framework_TestCase
{


    public function testCreate ()
    {
        $response = $this->getMock('Saml\Ecp\Response\ResponseInterface');
        $messages = array(
            'message 1', 
            'message 2'
        );
        $e = new InvalidResponseException($response, $messages);
        $this->assertSame($response, $e->getResponse());
        $this->assertSame($messages, $e->getMessages());
    }
}