<?php

namespace Saml\Ecp\Authentication\Method;

use Zend\Http\Client;
use Saml\Ecp\Exception as GeneralException;
use Saml\Ecp\Util\Options;


class BasicAuth extends AbstractMethod
{

    const OPT_USERNAME = 'username';

    const OPT_PASSWORD = 'password';


    /**
     * (non-PHPdoc)
     * @see \Saml\Ecp\Authentication\Method\AbstractMethod::validateOptions()
     */
    public function validateOptions (Options $options)
    {
        if (! $options->get(self::OPT_USERNAME)) {
            throw new GeneralException\MissingOptionException(self::OPT_USERNAME);
        }
        
        if (! $options->get(self::OPT_PASSWORD)) {
            throw new GeneralException\MissingOptionException(self::OPT_PASSWORD);
        }
    }


    /**
     * (non-PHPdoc)
     * @see \Saml\Ecp\Authentication\Method\AbstractMethod::configureHttpClient()
     */
    public function configureHttpClient (Client $httpClient)
    {
        $httpClient->setAuth($this->_options->get(self::OPT_USERNAME), $this->_options->get(self::OPT_PASSWORD), Client::AUTH_BASIC);
    }
}