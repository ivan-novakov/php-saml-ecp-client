<?php

namespace Saml\Ecp\Client;

use Saml\Ecp\Util\Options;


class Context extends Options
{

    const VAR_SP_ASSERTION_CONSUMER_URL = 'sp_assertion_consumer_url';


    /**
     * Sets the SP's assertion consumer URL.
     * 
     * @param string $url
     */
    public function setSpAssertionConsumerUrl ($url)
    {
        $this->set(self::VAR_SP_ASSERTION_CONSUMER_URL, $url);
    }


    /**
     * Returns the SP's assertion consumer URL.
     * 
     * @return string|null
     */
    public function getSpAssertionConsumerUrl ()
    {
        return $this->get(self::VAR_SP_ASSERTION_CONSUMER_URL);
    }
}