<?php

namespace Saml\Ecp\Response\Validator;

use Saml\Ecp\Response\ResponseInterface;


class Chain extends AbstractValidator
{

    /**
     * An array of validator objects.
     * 
     * @var array
     */
    protected $_validators = array();


    /**
     * (non-PHPdoc)
     * @see \Saml\Ecp\Response\Validator\ValidatorInterface::isValid()
     */
    public function isValid (ResponseInterface $response)
    {
        foreach ($this->_validators as $validator) {
            if (! $validator->isValid($response)) {
                $messages = $validator->getMessages();
                foreach ($messages as $message) {
                    $this->addMessage(sprintf("[%s] %s", get_class($validator), $message));
                }
                return false;
            }
        }
        
        return true;
    }


    /**
     * Adds a validator object to the chain.
     * 
     * @param ValidatorInterface $validator
     */
    public function addValidator (ValidatorInterface $validator)
    {
        $this->_validators[] = $validator;
    }


    /**
     * Returns all validators in the chain.
     * 
     * @return array
     */
    public function getValidators ()
    {
        return $this->_validators;
    }
}