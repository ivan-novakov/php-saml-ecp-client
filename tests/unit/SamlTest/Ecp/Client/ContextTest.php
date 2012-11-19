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


    public function testGetSpAssertionConsumerUrl ()
    {
        $url = 'https://some.url/';
        $this->_context->setSpAssertionConsumerUrl($url);
        $this->assertSame($url, $this->_context->getSpAssertionConsumerUrl());
    }
}