<?php

namespace SamlTest\Ecp\Client;

use Saml\Ecp\Client\Context;


class ContextTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Context
     */
    protected $_context = null;


    public function setUp ()
    {
        $this->_context = new Context();
    }


    public function testGetSpAuthnRequest ()
    {
        $request = $this->getMock('Saml\Ecp\Response\ResponseInterface');
        $this->_context->setSpInitialResponse($request);
        $this->assertSame($request, $this->_context->getSpInitialResponse());
    }
}