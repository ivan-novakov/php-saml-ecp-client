<?php

namespace Saml\Ecp\Client;

use Saml\Ecp\Response\ResponseInterface;
use Saml\Ecp\Util\Options;


/**
 * A simple class for storing information between requests.
 * 
 * @copyright (c) 2013 Ivan Novakov (http://novakov.cz/)
 * @license http://debug.cz/license/freebsd
 */
class Context extends Options
{

    /**
     * Option index.
     */
    const VAR_SP_AUTHN_REQUEST = 'sp_authn_request';


    /**
     * Stores the initial response sent by the SP (containing the authn request).
     * 
     * @param ResponseInterface $request
     */
    public function setSpInitialResponse(ResponseInterface $request)
    {
        $this->set(self::VAR_SP_AUTHN_REQUEST, $request);
    }


    /**
     * Returns a stored SP initial response.
     * 
     * @return ResponseInterface|null
     */
    public function getSpInitialResponse()
    {
        return $this->get(self::VAR_SP_AUTHN_REQUEST);
    }
}