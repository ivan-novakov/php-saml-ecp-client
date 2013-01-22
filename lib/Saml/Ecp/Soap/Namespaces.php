<?php

namespace Saml\Ecp\Soap;


/**
 * The class contains all "registered" namespaces used in SOAP messages.
 * 
 * @copyright (c) 2013 Ivan Novakov (http://novakov.cz/)
 * @license http://debug.cz/license/freebsd
 */
class Namespaces
{

    protected static $_namespaces = array(
        'S' => 'http://schemas.xmlsoap.org/soap/envelope/', 
        'saml' => 'urn:oasis:names:tc:SAML:2.0:assertion', 
        'samlp' => 'urn:oasis:names:tc:SAML:2.0:protocol', 
        'paos' => 'urn:liberty:paos:2003-08', 
        'ecp' => 'urn:oasis:names:tc:SAML:2.0:profiles:SSO:ecp'
    );


    static public function getAll()
    {
        return self::$_namespaces;
    }
}