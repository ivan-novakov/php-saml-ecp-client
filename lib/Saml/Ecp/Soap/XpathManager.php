<?php

namespace Saml\Ecp\Soap;


/**
 * The class creates and configures DOMXPath object instances.
 * 
 * @copyright (c) 2013 Ivan Novakov (http://novakov.cz/)
 * @license http://debug.cz/license/freebsd
 */
class XpathManager
{

    /**
     * The "registered" namespaces.
     * 
     * @var array
     */
    protected $_namespaces = array();


    /**
     * Constructor.
     * 
     * @param array $namespaces An array of namespaces (prefix => uri) to be registered within the DOMXPath objects.
     */
    public function __construct(array $namespaces = array())
    {
        $this->_namespaces = $namespaces;
    }


    /**
     * Creates and returns a DOMXPath object instance with registered namespaces.
     * 
     * @param \DomDocument $dom
     * @return \DOMXPath
     */
    public function getXpath(\DomDocument $dom)
    {
        $xpath = new \DOMXPath($dom);
        foreach ($this->_namespaces as $prefix => $uri) {
            $xpath->registerNamespace($prefix, $uri);
        }
        
        return $xpath;
    }
}