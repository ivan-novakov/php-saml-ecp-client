<?php

namespace Saml\Ecp\Soap;


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
     * Constructor.
     * 
     * @param string $soapData
     * @param XpathManager $xpathManager
     */
    public function __construct ($soapData = null, XpathManager $xpathManager = null)
    {
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
     */
    public function fromString ($soapData)
    {
        // FIXME try-catch
        $this->getDom()
            ->loadXML($soapData);
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


    public function getNamespaces ()
    {
        return Namespaces::getAll();
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
            $this->_xpathManager = new XpathManager($this->getNamespaces());
        }
        
        return $this->_xpathManager;
    }


    public function getXpath ()
    {
        return $this->getXpathManager()
            ->getXpath($this->getDom());
    }


    /**
     * Returns the S:Body element.
     * 
     * @return \DOMElement|NULL
     */
    public function getBody ()
    {
        $elements = $this->_getElementsByTagName('S', 'Body');
        if ($elements->length) {
            return $elements->item(0);
        }
        
        return null;
    }


    /**
     * Returns the list of the S:Body child elements.
     * 
     * @return \DOMNodeList
     */
    public function getBodyElements ()
    {
        $xpath = $this->getXpath();
        $elements = $xpath->query('/S:Envelope/S:Body/*');
        
        return $elements;
    }


    /**
     * Adds the provided element to the message body.
     * 
     * @param \DomElement $element
     */
    public function addBodyElement (\DomElement $element)
    {
        $dom = $this->getDom();
        $node = $dom->importNode($element, true);
        
        $body = $this->getBody();
        $body->appendChild($node);
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
    protected function _initDom ()
    {
        $dom = $this->getDom(true);
        $envelope = $dom->appendChild($this->_createElement('S', 'Envelope'));
        $envelope->appendChild($this->_createElement('S', 'Header'));
        $envelope->appendChild($this->_createElement('S', 'Body'));
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
     * @throws Exception\InvalidNamespaceException
     * @return string
     */
    protected function _getNamespaceUri ($prefix)
    {
        $namespaces = $this->getNamespaces();
        if (! isset($namespaces[$prefix])) {
            throw new Exception\InvalidNamespaceException($prefix);
        }
        
        return $namespaces[$prefix];
    }
}