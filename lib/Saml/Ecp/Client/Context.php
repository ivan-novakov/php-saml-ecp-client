<?php

namespace Saml\Ecp\Client;

use Saml\Ecp\Response\ResponseInterface;
use Saml\Ecp\Util\Options;


class Context extends Options
{

    const VAR_SP_AUTHN_REQUEST = 'sp_authn_request';


    /**
     * Stores the initial response sent by the SP (containing the authn request).
     * 
     * @param ResponseInterface $request
     */
    public function setSpInitialResponse (ResponseInterface $request)
    {
        $this->set(self::VAR_SP_AUTHN_REQUEST, $request);
    }


    /**
     * Returns a stored SP initial response.
     * 
     * @return ResponseInterface|null
     */
    public function getSpInitialResponse ()
    {
        return $this->get(self::VAR_SP_AUTHN_REQUEST);
    }
}