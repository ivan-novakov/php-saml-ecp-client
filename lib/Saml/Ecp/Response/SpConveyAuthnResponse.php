<?php

namespace Saml\Ecp\Response;


class SpConveyAuthnResponse extends Response
{


    public function validate ()
    {
        $this->_validateStatusCode();
    }
}