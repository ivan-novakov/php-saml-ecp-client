<?php

namespace Saml\Ecp\Client;

use Saml\Ecp\Request\RequestInterface;
use Saml\Ecp\Util\Options;


class Context extends Options
{

    const VAR_SP_AUTHN_REQUEST = 'sp_authn_request';


    public function setSpAuthnRequest (RequestInterface $request)
    {
        $this->set(self::VAR_SP_AUTHN_REQUEST, $request);
    }


    public function getSpAuthnRequest ()
    {
        return $this->get(self::VAR_SP_AUTHN_REQUEST);
    }
}