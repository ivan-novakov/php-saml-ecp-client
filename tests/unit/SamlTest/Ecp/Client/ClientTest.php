<?php

namespace SamlTest\Ecp\Client;

use Saml\Ecp\Client\Client;


class ClientTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Client
     */
    protected $_client = null;


    public function setUp ()
    {
        $this->_client = new Client();
    }


    public function testGetHttpClientWhichIsNotSet ()
    {
        $this->assertInstanceOf('Zend\Http\Client', $this->_client->getHttpClient());
    }


    public function testGetHttpClientWhichIsNotSetWithThrowException ()
    {
        $this->setExpectedException('Saml\Ecp\Exception\MissingDependencyException');
        $this->_client->getHttpClient(true);
    }


    public function testGetHttpClientAfterBeingSet ()
    {
        $httpClient = new \Zend\Http\Client();
        $this->_client->setHttpClient($httpClient);
        
        $this->assertSame($httpClient, $this->_client->getHttpClient());
    }
}