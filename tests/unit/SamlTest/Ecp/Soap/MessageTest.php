<?php

namespace SamlTest\Ecp\Soap;

use Saml\Ecp\Soap\Message;


class MessageTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Message
     */
    protected $_message = null;

    protected $_paosRequestService = 'urn:oasis:names:tc:SAML:2.0:profiles:SSO:ecp';

    protected $_paosRequestResponseConsumerUrl = 'https://hroch.cesnet.cz/Shibboleth.sso/SAML2/ECP';

    protected $_authnRequestAssertionConsumerServiceUrl = 'https://hroch.cesnet.cz/Shibboleth.sso/SAML2/ECP';


    public function setUp ()
    {
        $this->_message = new Message();
    }


    public function testConstructorWithNoSoapData ()
    {
        $dom = $this->_message->getDom();
        
        $this->assertInstanceOf('DomDocument', $dom);
        $this->assertSame($dom->documentElement->localName, 'Envelope');
        
        $nodeList = $dom->documentElement->childNodes;
        $this->assertSame(2, $nodeList->length);
        $this->assertSame('Header', $nodeList->item(0)->localName);
        $this->assertSame('Body', $nodeList->item(1)->localName);
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


    public function testGetXpathManagerNotSet ()
    {
        $this->assertInstanceOf('Saml\Ecp\Soap\XpathManager', $this->_message->getXpathManager());
    }


    public function testGetXpathManagerExplicitlySet ()
    {
        $xm = $this->getMock('Saml\Ecp\Soap\XpathManager');
        $this->_message->setXpathManager($xm);
        
        $this->assertSame($xm, $this->_message->getXpathManager());
    }


    public function testGetXpath ()
    {
        $this->assertInstanceOf('DomXpath', $this->_message->getXpath());
    }


    public function testGetBody ()
    {
        $this->_message->fromString($this->_getSoapData());
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


    public function testAddBodyElementImportNodeException ()
    {
        $this->setExpectedException('Saml\Ecp\Soap\Exception\ImportNodeException');
        
        $dom = $this->getMockBuilder('DomDocument')
            ->getMock();
        $dom->expects($this->once())
            ->method('importNode')
            ->will($this->throwException(new \DomException()));
        
        $this->_message->setDom($dom);
        
        $element = $this->getMockBuilder('DomElement')
            ->disableOriginalConstructor()
            ->getMock();
        $this->_message->addBodyElement($element);
    }


    public function testAppendChildToElement ()
    {
        $child = $child = $this->_getDomElementMock();
        
        $element = $child = $this->_getDomElementMock();
        $element->expects($this->once())
            ->method('appendChild')
            ->with($child);
        
        $this->_message->appendChildToElement($element, $child);
    }


    public function testAppendChildToElementThrowsError ()
    {
        $this->setExpectedException('Saml\Ecp\Soap\Exception\AppendChildException');
        
        $child = $this->_getDomElementMock();
        
        $element = $child = $this->_getDomElementMock();
        $element->expects($this->once())
            ->method('appendChild')
            ->with($child)
            ->will($this->throwException(new \DomException()));
        
        $this->_message->appendChildToElement($element, $child);
    }


    public function testCopyBodyFromMessage ()
    {
        $srcMessage = new Message($this->_getSoapData());
        
        $this->_message->copyBodyFromMessage($srcMessage);
        $this->assertEquals($srcMessage->getBodyElements(), $this->_message->getBodyElements());
    }


    public function testGetPaosRequestService ()
    {
        $this->_message->fromString($this->_getSoapData());
        $this->assertSame($this->_paosRequestService, $this->_message->getPaosRequestService());
    }


    public function testGetPaosRequestResponseConsumerUrl ()
    {
        $this->_message->fromString($this->_getSoapData());
        $this->assertSame($this->_paosRequestResponseConsumerUrl, $this->_message->getPaosRequestResponseConsumerUrl());
    }


    public function testGetAuthnRequestAssertionConsumerServiceUrl ()
    {
        $this->_message->fromString($this->_getSoapData());
        $this->assertSame($this->_authnRequestAssertionConsumerServiceUrl, $this->_message->getAuthnRequestAssertionConsumerServiceUrl());
    }


    protected function _getSoapData ()
    {
        return file_get_contents(TESTS_FILES_DIR . 'soap-saml-authn-request.xml');
    }


    protected function _getDomElementMock ()
    {
        return $this->getMockBuilder('DomElement')
            ->disableOriginalConstructor()
            ->getMock();
    }
}