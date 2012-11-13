<?php

namespace Saml\Ecp\Request;


class IdpAuthnRequest extends AbstractRequest
{


    protected function _init ()
    {
        $this->getHttpRequest()
            ->setMethod(\Zend\Http\Request::METHOD_POST);
    }
}