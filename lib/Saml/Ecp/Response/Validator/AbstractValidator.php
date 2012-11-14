<?php

namespace Saml\Ecp\Response\Validator;

use Saml\Ecp\Util\Options;


/**
 * Abstract response validator class.
 *
 */
abstract class AbstractValidator implements ValidatorInterface
{

    /**
     * Options.
     * 
     * @var Options
     */
    protected $_options = null;

    /**
     * Validation error messages.
     * 
     * @var array
     */
    protected $_messages = array();


    /**
     * Constructor.
     * 
     * @param array|\Trversable $optios
     */
    public function __construct ($options = array())
    {
        $this->setOptions($options);
    }


    /**
     * Sets the options.
     * 
     * @param array|\Trversable $optios
     */
    public function setOptions ($options)
    {
        $this->_options = new Options($options);
    }


    /**
     * Returns the options.
     * 
     * @return Options
     */
    public function getOptions ()
    {
        return $this->_options;
    }


    /**
     * Returns the required option.
     * 
     * @param string $name
     * @param mixed $defaultValue
     * @return mixed
     */
    public function getOption ($name, $defaultValue = null)
    {
        return $this->_options->get($name, $defaultValue);
    }


    /**
     * Adds a validation error message.
     * 
     * @param string $message
     */
    public function addMessage ($message)
    {
        $this->_messages[] = $message;
    }


    /**
     * (non-PHPdoc)
     * @see \Saml\Ecp\Response\Validator\ValidatorInterface::getMessages()
     */
    public function getMessages ()
    {
        return $this->_messages;
    }
}