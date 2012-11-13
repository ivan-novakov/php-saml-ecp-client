<?php

namespace Saml\Ecp\Request;

use Saml\Ecp\Client\MimeType;


class SpInitialRequest extends AbstractRequest
{

    const URN_ECP = 'urn:oasis:names:tc:SAML:2.0:profiles:SSO:ecp';


    protected function _init ()
    {
        $this->setHeader('Accept', MimeType::PAOS);
        
        $paosVersion = $this->_options->get('paos_version', 'urn:liberty:paos:2003-08');
        $this->setHeader('PAOS', sprintf("ver=\"%s\";\"%s\"", $paosVersion, self::URN_ECP));
    }
}