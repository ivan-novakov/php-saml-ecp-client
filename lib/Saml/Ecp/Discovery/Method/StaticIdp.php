<?php

namespace Saml\Ecp\Discovery\Method;

use Saml\Ecp\Exception as GeneralException;


/**
 * A static IdP discovery method. It simply returns the IdP endpoint URL, which is set as an option through
 * the constructor.
 * 
 * @copyright (c) 2013 Ivan Novakov (http://novakov.cz/)
 * @license http://debug.cz/license/freebsd
 */
class StaticIdp extends AbstractMethod
{

    /**
     * Option index.
     */
    const OPT_IDP_ECP_ENDPOINT = 'idp_ecp_endpoint';


    /**
     * {@inheritdoc}
     * @see \Saml\Ecp\Discovery\Method\MethodInterface::getIdpEcpEndpoint()
     */
    public function getIdpEcpEndpoint()
    {
        $endpoint = $this->_options->get(self::OPT_IDP_ECP_ENDPOINT);
        if (null === $endpoint) {
            throw new GeneralException\MissingOptionException(self::OPT_IDP_ECP_ENDPOINT);
        }
        
        return $endpoint;
    }
}