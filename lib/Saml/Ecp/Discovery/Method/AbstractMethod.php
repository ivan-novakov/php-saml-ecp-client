<?php

namespace Saml\Ecp\Discovery\Method;

use Saml\Ecp\Util\Options;


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
     * 
     * @param array|\Traversable $options
     */
    public function __construct ($options = array())
    {
        $this->setOptions($options);
    }


    /**
     * Sets the options.
     * 
     * @param array|\Traversable $options
     */
    public function setOptions ($options)
    {
        $this->_options = new Options($options);
    }
}