<?php

namespace SamlIntegrationTest\Ecp\Soap;

use Saml\Ecp\Soap\Message;


class MessageTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Message
     */
    protected $_message = null;


    public function setUp ()
    {
        $this->_message = new Message($this->_getSoapData());
    }


    public function testFromString ()
    {
        $this->_message->fromString($this->_getSoapData());
        $dom = $this->_message->getDom();
        
        $this->assertInstanceOf('DomDocument', $dom);
    }


    public function testFromBadString ()
    {
        $this->setExpectedException('Saml\Ecp\Soap\Exception\LoadSoapDataException');
        $this->_message->fromString('bad XML string');
    }


    public function testGetBody ()
    {
        $body = $this->_message->getBody();
        
        $this->assertInstanceOf('DomElement', $body);
        $this->assertSame('Body', $body->localName);
    }
    
    // FIXME - more specific
    public function testGetHeaderElements ()
    {
        $this->_message->fromString($this->_getSoapData());
        $headerElements = $this->_message->getHeaderElements();
        
        $this->assertInstanceOf('DomNodeList', $headerElements);
        $this->assertSame(3, $headerElements->length);
    }
    
    // FIXME - more specific
    public function testGetBodyElements ()
    {
        $this->_message->fromString($this->_getSoapData());
        $bodyElements = $this->_message->getBodyElements();
        
        $this->assertInstanceOf('DomNodeList', $bodyElements);
        $this->assertSame(1, $bodyElements->length);
    }


    public function testAddBodyElement ()
    {
        $bodyElement = new \DOMElement('TestElement');
        $this->_message->addBodyElement($bodyElement);
        $bodyElements = $this->_message->getBodyElements();
        
        $this->assertEquals($bodyElement, $bodyElements->item(0));
    }


    public function testCopyBodyFromMessage ()
    {
        $srcMessage = new Message($this->_getSoapData());
        
        $this->_message->copyBodyFromMessage($srcMessage);
        $this->assertEquals($srcMessage->getBodyElements(), $this->_message->getBodyElements());
    }


    /**
     * @dataProvider dataProviderGetNodeByXpath
     */
    public function testGetNodeByXpath ($xpathQuery, $className, $nodeName)
    {
        $node = $this->_message->getNodeByXpath($xpathQuery);
        $this->assertInstanceOf($className, $node);
        $this->assertSame($nodeName, $node->nodeName);
    }


    /**
     * @dataProvider dataProvidergetNodeValueByXpath
     */
    public function testGetNodeValueByXpath ($xpathQuery, $nodeValue)
    {
        $this->_message->fromString($this->_getSoapData());
        $this->assertSame($nodeValue, $this->_message->getNodeValueByXpath($xpathQuery));
    }


    public function dataProviderGetNodeByXpath ()
    {
        return array(
            array(
                '/S:Envelope/S:Header/ecp:Request', 
                'DomNode', 
                'ecp:Request'
            ), 
            array(
                '/S:Envelope/S:Header/ecp:Request/@IsPassive', 
                'DomAttr', 
                'IsPassive'
            )
        );
    }


    public function dataProvidergetNodeValueByXpath ()
    {
        return array(
            array(
                '/S:Envelope/S:Header/ecp:RelayState', 
                'ss:mem:cca27ceabb8b0035ead1d99c0e343fa9234d6d559d5da43d4efb9d6cb592c0cf'
            ), 
            array(
                '/S:Envelope/S:Header/paos:Request/@S:actor', 
                'http://schemas.xmlsoap.org/soap/actor/next'
            )
        );
    }


    protected function _getSoapData ()
    {
        return file_get_contents(TESTS_FILES_DIR . 'soap/soap-saml-authn-request.xml');
    }
}