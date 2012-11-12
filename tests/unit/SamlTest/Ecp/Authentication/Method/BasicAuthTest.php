<?php

namespace SamlTest\Ecp\Authentication\Method;

use Saml\Ecp\Authentication\Method\BasicAuth;


class BasicAuthTest extends \PHPUnit_Framework_TestCase
{


    public function testValidateOptionsOk ()
    {
        $options = array(
            BasicAuth::OPT_USERNAME => 'testuser', 
            BasicAuth::OPT_PASSWORD => 'testpasswd'
        );
        
        $method = new BasicAuth($options);
        $this->assertSame($options, $method->getOptions()
            ->toArray());
    }


    public function testValidateOptionsNoUsername ()
    {
        $this->setExpectedException('Saml\Ecp\Exception\MissingOptionException');
        
        $options = array(
            BasicAuth::OPT_USERNAME => 'testuser'
        );
        
        $method = new BasicAuth($options);
    }


    public function testValidateOptionsNoPassword ()
    {
        $this->setExpectedException('Saml\Ecp\Exception\MissingOptionException');
        
        $options = array(
            BasicAuth::OPT_PASSWORD => 'testpasswd'
        );
        
        $method = new BasicAuth($options);
    }


    public function testConfigureHttpClient ()
    {
        $options = array(
            BasicAuth::OPT_USERNAME => 'testuser', 
            BasicAuth::OPT_PASSWORD => 'testpasswd'
        );
        
        $method = new BasicAuth($options);
        
        $httpClient = $this->getMockBuilder('Zend\Http\Client')
            ->getMock();
        $httpClient->expects($this->once())
            ->method('setAuth')
            ->with('testuser', 'testpasswd', \Zend\Http\Client::AUTH_BASIC);
        
        $method->configureHttpClient($httpClient);
    }
}