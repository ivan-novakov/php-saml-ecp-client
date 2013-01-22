<?php

namespace Saml\Ecp\Request;

use Saml\Ecp\Soap\Message;
use Saml\Ecp\Client\MimeType;


/**
 * The request relays the authn response from the IdP to the SP.
 * 
 * @copyright (c) 2013 Ivan Novakov (http://novakov.cz/)
 * @license http://debug.cz/license/freebsd
 */
class SpConveyAuthnRequest extends AbstractRequest
{


    /**
     * {@inheritdoc}
     * @see \Saml\Ecp\Request\AbstractRequest::_init()
     */
    protected function _init()
    {
        $httpRequest = $this->getHttpRequest();
        $httpRequest->setMethod(\Zend\Http\Request::METHOD_POST);
        $httpRequest->getHeaders()
            ->addHeaders(array(
            'Content-Type' => MimeType::PAOS
        ));
    }
}