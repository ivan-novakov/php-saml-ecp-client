<?php

namespace Saml\Ecp\Request;

use Saml\Ecp\Soap\Message\AuthnRequest;


class IdpAuthnRequest extends AbstractRequest
{


    protected function _init ()
    {
        $this->getHttpRequest()
            ->setMethod(\Zend\Http\Request::METHOD_POST);
    }


    /**
     * (non-PHPdoc)
     * @see \Saml\Ecp\Request\AbstractRequest::_createSoapMessage()
     */
    protected function _createSoapMessage ()
    {
        return new AuthnRequest();
    }
}