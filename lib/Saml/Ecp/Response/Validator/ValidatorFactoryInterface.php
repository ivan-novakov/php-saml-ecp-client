<?php

namespace Saml\Ecp\Response\Validator;


/**
 * Validator factory interface.
 * 
 * @copyright (c) 2013 Ivan Novakov (http://novakov.cz/)
 * @license http://debug.cz/license/freebsd
 */
interface ValidatorFactoryInterface
{


    /**
     * Creates a validator to validate the initial SP response.
     * 
     * @return ValidatorInterface
     */
    public function createSpInitialResponseValidator(array $options = array());


    /**
     * Creates a validator to validate an IdP authn response.
     * 
     * @return ValidatorInterface
     */
    public function createIdpAuthnResponseValidator(array $options = array());
}