<?php

namespace Saml\Ecp\Request;

use Saml\Ecp\Soap\Message;
use Saml\Ecp\Client\MimeType;


class SpConveyAuthnRequest extends AbstractRequest
{


    protected function _init ()
    {
        $httpRequest = $this->getHttpRequest();
        $httpRequest->setMethod(\Zend\Http\Request::METHOD_POST);
        $httpRequest->getHeaders()
            ->addHeaders(array(
            'Content-Type' => MimeType::PAOS
        ));
    }
}