<?php

namespace Saml\Ecp\Response;

use Saml\Ecp\Soap\Message\AuthnRequest;


/**
 * The response sent by the SP, after the initial request for protected resource. The response must contain a SAML 
 * AuthnRequest SOAP message.
 * 
 * @copyright (c) 2013 Ivan Novakov (http://novakov.cz/)
 * @license http://debug.cz/license/freebsd
 */
class SpInitialResponse extends AbstractResponse
{


    /**
     * {@inheritdoc}
     * @see \Saml\Ecp\Response\AbstractResponse::_createSoapMessage()
     * @return AuthnRequest
     */
    protected function _createSoapMessage($content)
    {
        return new AuthnRequest($content);
    }
}