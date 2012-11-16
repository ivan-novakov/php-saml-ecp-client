<?php

namespace Saml\Ecp\Protocol;


interface AuthnRequestInterface
{


    /**
     * Returns the value of the "service" attribute of the paos:Request header element.
     *
     * XPath: /S:Envelope/S:Header/paos:Request/@service
     *
     * @return string|null
     */
    public function getPaosRequestService ();


    /**
     * Returns the value of the "AssertionConsumerServiceURL" attribute of the samlp:AuthnRequest element.
     *
     * XPath: /S:Envelope/S:Body/samlp:AuthnRequest/@AssertionConsumerServiceURL
     *
     * @return string|null
     */
    public function getAssertionConsumerServiceUrl ();


    /**
     * Returns the value of the "responseConsumerURL" attribute of the paos:Request header element.
     *
     * XPath: /S:Envelope/S:Header/paos:Request/@responseConsumerURL
     *
     * @return string|null
     */
    public function getPaosResponseConsumerUrl ();
}