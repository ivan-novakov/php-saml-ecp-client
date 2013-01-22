<?php

namespace Saml\Ecp\Request;

use Saml\Ecp\Soap\Message\AuthnRequest;


/**
 * Authentication request to be sent to the IdP.
 * 
 * @copyright (c) 2013 Ivan Novakov (http://novakov.cz/)
 * @license http://debug.cz/license/freebsd
 */
class IdpAuthnRequest extends AbstractRequest
{


    /**
     * {@inheritdoc}
     * @see \Saml\Ecp\Request\AbstractRequest::_init()
     */
    protected function _init()
    {
        $this->getHttpRequest()
            ->setMethod(\Zend\Http\Request::METHOD_POST);
    }


    /**
     * {@inheritdoc}
     * @see \Saml\Ecp\Request\AbstractRequest::_createSoapMessage()
     */
    protected function _createSoapMessage()
    {
        return new AuthnRequest();
    }
}