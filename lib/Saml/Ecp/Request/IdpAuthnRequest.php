<?php

namespace Saml\Ecp\Request;


class IdpAuthnRequest extends Request
{


    protected function _init ()
    {
        $this->getHttpRequest()
            ->setMethod(\Zend\Http\Request::METHOD_POST);
    }
}