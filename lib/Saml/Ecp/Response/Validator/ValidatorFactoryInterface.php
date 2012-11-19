<?php

namespace Saml\Ecp\Response\Validator;


interface ValidatorFactoryInterface
{


    /**
     * Creates a validator to validate the initial SP response.
     * 
     * @return ValidatorInterface
     */
    public function createSpInitialResponseValidator (array $options = array());


    /**
     * Creates a validator to validate an IdP authn response.
     * 
     * @return ValidatorInterface
     */
    public function createIdpAuthnResponseValidator (array $options = array());
}