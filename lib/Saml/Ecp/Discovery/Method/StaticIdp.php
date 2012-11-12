<?php

namespace Saml\Ecp\Discovery\Method;

use Saml\Ecp\Exception as GeneralException;


class StaticIdp extends AbstractMethod
{

    const OPT_IDP_ECP_ENDPOINT = 'idp_ecp_endpoint';


    /**
     * (non-PHPdoc)
     * @see \Saml\Ecp\Discovery\Method\MethodInterface::getIdpEcpEndpoint()
     */
    public function getIdpEcpEndpoint ()
    {
        $endpoint = $this->_options->get(self::OPT_IDP_ECP_ENDPOINT);
        if (null === $endpoint) {
            throw new GeneralException\MissingOptionException(self::OPT_IDP_ECP_ENDPOINT);
        }
        
        return $endpoint;
    }
}