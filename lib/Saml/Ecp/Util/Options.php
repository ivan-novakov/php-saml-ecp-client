<?php

namespace Saml\Ecp\Util;


class Options extends \ArrayObject
{


    /**
     * Constructor.
     *
     * @param array|\Traversable $options
     */
    public function __construct ($options = NULL)
    {
        if (NULL === $options) {
            $options = array();
        } else {
            $options = \Zend\Stdlib\ArrayUtils::iteratorToArray($options);
        }
        
        parent::__construct($options);
    }


    /**
     * Returns the option for the corresponding key.
     *
     * @param string $key
     * @param mixed $defaultValue
     * @return mixed|NULL
     */
    public function get ($key, $defaultValue = NULL)
    {
        if ($this->offsetExists($key)) {
            return $this->offsetGet($key);
        }
        
        if (NULL !== $defaultValue) {
            return $defaultValue;
        }
        
        return NULL;
    }


    /**
     * Sets the value to with the corresponding key.
     *
     * @param string $key
     * @param mixed $value
     */
    public function set ($key, $value)
    {
        $this->offsetSet($key, $value);
    }


    /**
     * Returns the object as an array.
     * 
     * @return array
     */
    public function toArray ()
    {
        return (array) $this;
    }
}