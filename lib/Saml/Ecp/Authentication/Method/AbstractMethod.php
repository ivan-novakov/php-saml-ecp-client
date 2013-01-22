<?php

namespace Saml\Ecp\Authentication\Method;

use Zend\Http\Client;
use Saml\Ecp\Util\Options;
use Saml\Ecp\Exception as GeneralException;


/**
 * Abstract authentication method class.
 * 
 * @copyright (c) 2013 Ivan Novakov (http://novakov.cz)
 * @license http://debug.cz/license/freebsd
 */
abstract class AbstractMethod implements MethodInterface
{

    /**
     * Options.
     * 
     * @var Options
     */
    protected $_options = null;


    /**
     * Constructor.
     * @param array|\Traversable $options
     */
    public function __construct($options = array())
    {
        $this->setOptions($options);
    }


    /**
     * Sets the options.
     * 
     * @param array|\Traversable $options
     */
    public function setOptions($options)
    {
        $options = new Options($options);
        $this->validateOptions($options);
        
        $this->_options = $options;
    }


    /**
     * Returns the options.
     * 
     * @return Options
     */
    public function getOptions()
    {
        return $this->_options;
    }


    /**
     * Validates the provided options.
     * 
     * @param Options $options
     * @throws GeneralException\MissingOptionException
     */
    public function validateOptions(Options $options)
    {}
}