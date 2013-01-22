<?php

namespace Saml\Ecp\Request;

use Saml\Ecp\Client\MimeType;


/**
 * The initial request sent to the SP - the first request in the flow.
 * 
 * @copyright (c) 2013 Ivan Novakov (http://novakov.cz/)
 * @license http://debug.cz/license/freebsd
 */
class SpInitialRequest extends AbstractRequest
{

    /**
     * The ECP profile URN identificator.
     */
    const URN_ECP = 'urn:oasis:names:tc:SAML:2.0:profiles:SSO:ecp';


    /**
     * {@inheritdoc}
     * @see \Saml\Ecp\Request\AbstractRequest::_init()
     */
    protected function _init()
    {
        $this->setHeader('Accept', MimeType::PAOS);
        
        $paosVersion = $this->_options->get('paos_version', 'urn:liberty:paos:2003-08');
        $this->setHeader('PAOS', sprintf("ver=\"%s\";\"%s\"", $paosVersion, self::URN_ECP));
    }
}