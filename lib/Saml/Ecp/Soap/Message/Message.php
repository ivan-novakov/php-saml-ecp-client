<?php

namespace Saml\Ecp\Soap\Message;

use Saml\Ecp\Soap\XpathManager;
use Saml\Ecp\Soap\Namespaces;
use Saml\Ecp\Soap\Exception as SoapException;
use Zend\Stdlib\ErrorHandler;


class Message
{

    /**
     * The DOM object representing the SOAP message.
     * 
     * @var \DOMDocument
     */
    protected $_dom = null;

    /**
     * The XPath manager.
     *
     * @var XpathManager
     */
    protected $_xpathManager = null;

    /**
     * The DOM Xpath object.
     * 
     * @var \DomXpath
     */
    protected $_xpath = null;

    /**
     * The SOAP elements prefix to be used.
     * 
     * @var string
     */
    protected $_soapNsPrefix = 'S';

    /**
     * A list of registered XML namespaces.
     * 
     * @var array
     */
    protected $_namespaces = array();


    /**
     * Constructor.
     * 
     * @param string $soapData
     * @param XpathManager $xpathManager
     */
    public function __construct ($soapData = null, XpathManager $xpathManager = null)
    {
        // FIXME - get rid of the static call
        $this->_namespaces = Namespaces::getAll();
        
        if (null !== $soapData) {
            $this->fromString($soapData);
        } else {
            $this->_initDom();
        }
        
        if (null !== $xpathManager) {
            $this->setXpathManager($xpathManager);
        }
    }


    /**
     * Loads the SOAP message from a string.
     * 
     * @param string $soapData
     * @throws SoapException\LoadSoapDataException
     */
    public function fromString ($soapData)
    {
        $dom = $this->getDom();
        
        try {
            ErrorHandler::start();
            $dom->loadXML($soapData);
            ErrorHandler::stop(true);
        } catch (\Exception $e) {
            if (ErrorHandler::started()) {
                ErrorHandler::stop();
            }
            throw new SoapException\LoadSoapDataException($e->getMessage());
        }
        
        $rootElement = $dom->documentElement;
        if ('envelope' != strtolower($rootElement->localName)) {
            throw new SoapException\LoadSoapDataException(sprintf("Invalid root element '%s'", $rootElement->localName));
        }
    }


    /**
     * Returns the DOM representation of the SOAP message.
     * 
     * @return \DOMDocument
     */
    public function getDom ($reset = false)
    {
        if ($reset || ! ($this->_dom instanceof \DOMDocument)) {
            $this->_dom = $this->_createDom();
        }
        
        return $this->_dom;
    }


    /**
     * Sets the DOM representation of the SOAP message.
     * 
     * @param \DomDocument $dom
     */
    public function setDom (\DomDocument $dom)
    {
        $this->_dom = $dom;
        $this->_xpath = null;
    }


    /**
     * Returns all the XML namespaces used in the current SOAP message.
     * 
     * The keys are the prefixes, the values are the URIs of the namespaces.
     * 
     * @return array
     */
    public function getRegisteredNamespaces ()
    {
        return $this->_namespaces;
    }


    /**
     * Sets the XPath manager.
     *
     * @param XpathManager $xpathManager
     */
    public function setXpathManager (XpathManager $xpathManager)
    {
        $this->_xpathManager = $xpathManager;
    }


    /**
     * Returns the XPath manager.
     *
     * @return \Saml\Ecp\Soap\XpathManager
     */
    public function getXpathManager ()
    {
        if (! ($this->_xpathManager instanceof XpathManager)) {
            $this->_xpathManager = new XpathManager($this->getRegisteredNamespaces());
        }
        
        return $this->_xpathManager;
    }


    /**
     * Returns properly configured DomXpath object.
     * 
     * @return \DOMXPath
     */
    public function getXpath ()
    {
        if (! ($this->_xpath instanceof \DomXpath)) {
            $this->_xpath = $this->getXpathManager()
                ->getXpath($this->getDom());
        }
        
        return $this->_xpath;
    }


    /**
     * Sets the DomXpath object.
     * 
     * @param \DomXpath $xpath
     */
    public function setXpath ($xpath)
    {
        $this->_xpath = $xpath;
    }


    /**
     * Returns the S:Body element.
     * 
     * @return \DOMElement|NULL
     */
    public function getBody ()
    {
        $elements = $this->_getElementsByTagName($this->_soapNsPrefix, 'Body');
        if ($elements->length) {
            return $elements->item(0);
        }
        
        return null;
    }


    /**
     * Returns the list of S:Header elements.
     * 
     * @return \DOMNodeList
     */
    public function getHeaderElements ()
    {
        $xpath = $this->getXpath();
        $elements = $xpath->query(sprintf("/%s:Envelope/%s:Header/*", $this->_soapNsPrefix, $this->_soapNsPrefix));
        
        return $elements;
    }


    /**
     * Returns the list of the S:Body child elements.
     * 
     * @return \DOMNodeList
     */
    public function getBodyElements ()
    {
        $xpath = $this->getXpath();
        $elements = $xpath->query(sprintf("/%s:Envelope/%s:Body/*", $this->_soapNsPrefix, $this->_soapNsPrefix));
        
        return $elements;
    }


    /**
     * Adds the provided element to the message body.
     * 
     * @param \DomElement $element
     * @throws SoapException\ImportNodeException
     */
    public function addBodyElement (\DomElement $element)
    {
        $dom = $this->getDom();
        
        try {
            $node = $dom->importNode($element, true);
        } catch (\Exception $e) {
            throw new SoapException\ImportNodeException(sprintf("Error importing node: [%s] %s", get_class($e), $e->getMessage()));
        }
        
        $body = $this->getBody();
        
        $this->appendChildToElement($body, $node);
    }


    /**
     * Copies the body elements from the provided SOAP message.
     * 
     * @param Message $message
     */
    public function copyBodyFromMessage (Message $message)
    {
        $bodyElements = $message->getBodyElements();
        
        foreach ($bodyElements as $element) {
            $this->addBodyElement($element);
        }
    }


    /**
     * Appends an element as a child to another element.
     * 
     * @param \DomElement $element
     * @param \DomElement $child
     * @throws SoapException\AppendChildException
     */
    public function appendChildToElement (\DomElement $element, \DomElement $child)
    {
        try {
            return $element->appendChild($child);
        } catch (\Exception $e) {
            throw new SoapException\AppendChildException(sprintf("Error appending element: [%s] %s", get_class($e), $e->getMessage()));
        }
    }


    /**
     * Returns the (first) node from the XPath query result.
     * 
     * @param string $xpathQuery
     * @return \DOMNode|null
     */
    public function getNodeByXpath ($xpathQuery)
    {
        $nodes = $this->getXpath()
            ->query($xpathQuery);
        
        if ($nodes->length) {
            return $nodes->item(0);
        }
        
        return null;
    }


    /**
     * Returns the value of the (first) node returned by the XPath query.
     * 
     * @param string $xpathQuery
     * @return string|null     
     */
    public function getNodeValueByXpath ($xpathQuery)
    {
        $node = $this->getNodeByXpath($xpathQuery);
        if ($node instanceof \DOMNode) {
            return trim($node->nodeValue);
        }
        
        return null;
    }


    /**
     * Returns a node list as a result of a XPath query.
     * 
     * @param string $xpathQuery
     * @return \DOMNodeList
     */
    public function xpathQuery ($xpathQuery)
    {
        return $this->getXpath()
            ->query($xpathQuery);
    }


    /**
     * Returns the XML string representing the message.
     * 
     * @return string
     */
    public function toString ()
    {
        return $this->getDom()
            ->saveXML();
    }


    /**
     * The magic method.
     * 
     * @return string
     */
    public function __toString ()
    {
        return $this->toString();
    }
    
    /*
     * Protected/private
     */
    
    /**
     * Creates an empty DOM document object.
     * 
     * @return \DOMDocument
     */
    protected function _createDom ()
    {
        return new \DOMDocument('1.0', 'utf-8');
    }


    /**
     * Initializes SOAP envelope with empty header and body.
     */
    protected function _initDom ($soapPrefix = null)
    {
        if (null === $soapPrefix) {
            $soapPrefix = $this->_soapNsPrefix;
        }
        
        $dom = $this->getDom(true);
        $envelope = $dom->appendChild($this->_createElement($soapPrefix, 'Envelope'));
        $envelope->appendChild($this->_createElement($soapPrefix, 'Header'));
        $envelope->appendChild($this->_createElement($soapPrefix, 'Body'));
    }


    /**
     * Creates and returns an element with the provided prefix and name.
     * 
     * @param string $prefix
     * @param string $name
     * @return \DOMElement
     */
    protected function _createElement ($prefix, $name)
    {
        $dom = $this->getDom();
        $nsUri = $this->_getNamespaceUri($prefix);
        $element = $dom->createElementNS($nsUri, $prefix . ':' . $name);
        
        return $element;
    }


    /**
     * Returns a list of elements with the provided prefix/name.
     * 
     * @param string $prefix
     * @param string $name
     * @return \DOMNodeList
     */
    protected function _getElementsByTagName ($prefix, $name)
    {
        $dom = $this->getDom();
        $nsUri = $this->_getNamespaceUri($prefix);
        
        return $dom->getElementsByTagNameNS($nsUri, $name);
    }


    /**
     * Returns the corresponding URI for the provided prefix.
     * 
     * @param string $prefix
     * @throws SoapException\InvalidNamespaceException
     * @return string
     */
    protected function _getNamespaceUri ($prefix)
    {
        $namespaces = $this->getRegisteredNamespaces();
        if (! isset($namespaces[$prefix])) {
            throw new SoapException\InvalidNamespaceException($prefix);
        }
        
        return $namespaces[$prefix];
    }
}