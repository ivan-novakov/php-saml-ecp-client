<?php

namespace Saml\Ecp\Soap;


class XpathManager
{

    protected $_namespaces = array();


    public function __construct (array $namespaces = array())
    {
        $this->_namespaces = $namespaces;
    }


    public function getXpath (\DomDocument $dom)
    {
        $xpath = new \DOMXPath($dom);
        foreach ($this->_namespaces as $prefix => $uri) {
            $xpath->registerNamespace($prefix, $uri);
        }
        
        return $xpath;
    }
}