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
        $xpath = $this->getMockBuilder('DomXpath')
            ->disableOriginalConstructor()
            ->getMock();
        
        $xpathManager = $this->getMock('Saml\Ecp\Soap\XpathManager');
        $xpathManager->expects($this->once())
            ->method('getXpath')
            ->with($this->isInstanceOf('DomDocument'))
            ->will($this->returnValue($xpath));
        
        $this->_message->setXpathManager($xpathManager);
        
        $this->assertSame($xpath, $this->_message->getXpath());
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