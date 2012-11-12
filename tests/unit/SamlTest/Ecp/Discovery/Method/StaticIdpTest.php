<?php

namespace SamlTest\Ecp\Discovery\Method;

use Saml\Ecp\Discovery\Method\StaticIdp;


class StaticIdpTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var StaticIdp
     */
    protected $_discovery = null;


    public function setUp ()
    {
        $this->_discovery = new StaticIdp();
    }


    public function testGetIdpEcpEndpoint ()
    {
        $endpoint = 'https://test';
        $this->_discovery->setOptions(array(
            StaticIdp::OPT_IDP_ECP_ENDPOINT => $endpoint
        ));
        
        $this->assertSame($endpoint, $this->_discovery->getIdpEcpEndpoint());
    }


    public function testGetIdpEcpEndpointNoEndpointOption ()
    {
        $this->setExpectedException('Saml\Ecp\Exception\MissingOptionException');
        $this->_discovery->getIdpEcpEndpoint();
    }
}