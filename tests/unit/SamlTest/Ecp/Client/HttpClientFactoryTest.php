<?php

namespace SamlTest\Ecp\Client;

use Saml\Ecp\Client\HttpClientFactory;


class HttpClientFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var HttpClientFactory
     */
    protected $_factory = null;


    public function setUp ()
    {
        $this->_factory = new HttpClientFactory();
    }


    public function testCreateWithNoOptions ()
    {
        $this->setExpectedException('Saml\Ecp\Exception\MissingConfigException');
        $this->_factory->createHttpClient(array());
    }


    public function testCreateWithNoCaOptions ()
    {
        $this->setExpectedException('Saml\Ecp\Exception\MissingConfigException');
        $this->_factory->createHttpClient(array(
            'options' => array()
        ));
    }


    public function testCreateWithCaFile ()
    {
        $cafile = '/some/file';
        $client = $this->_factory->createHttpClient(array(
            'options' => array(
                HttpClientFactory::OPT_CAFILE => $cafile
            )
        ));
        
        $this->assertInstanceOf('Zend\Http\Client', $client);
        
        $adapterConfig = $client->getAdapter()
            ->getConfig();
        
        $this->assertSame($cafile, $adapterConfig['curloptions'][CURLOPT_CAINFO]);
    }


    public function testCreateWithCaPath ()
    {
        $capath = '/some/path';
        $client = $this->_factory->createHttpClient(array(
            'options' => array(
                HttpClientFactory::OPT_CAPATH => $capath
            )
        ));
        
        $this->assertInstanceOf('Zend\Http\Client', $client);
        
        $adapterConfig = $client->getAdapter()
            ->getConfig();
        $this->assertSame($capath, $adapterConfig['curloptions'][CURLOPT_CAPATH]);
    }


    public function testCreateWithZendClientOptions ()
    {
        $capath = '/some/path';
        $userAgent = 'test user agent';
        $client = $this->_factory->createHttpClient(array(
            'options' => array(
                HttpClientFactory::OPT_CAPATH => $capath
            ), 
            'zend_client_options' => array(
                'useragent' => $userAgent
            )
        ));
        
        $adapterConfig = $client->getAdapter()
            ->getConfig();
        
        $this->assertSame($userAgent, $adapterConfig['useragent']);
    }


    public function testCreateWithAdapterOptions ()
    {
        $capath = '/some/path';
        $maxRedirs = 4;
        $client = $this->_factory->createHttpClient(array(
            'options' => array(
                HttpClientFactory::OPT_CAPATH => $capath
            ), 
            'curl_adapter_options' => array(
                CURLOPT_MAXREDIRS => $maxRedirs
            )
        ));
        
        $adapterConfig = $client->getAdapter()
            ->getConfig();
        
        $this->assertSame($maxRedirs, $adapterConfig['curloptions'][CURLOPT_MAXREDIRS]);
    }
}