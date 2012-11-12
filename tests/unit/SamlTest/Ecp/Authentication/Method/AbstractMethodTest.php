<?php

namespace SamlTest\Ecp\Authentication\Method;

use Saml\Ecp\Authentication\Method\AbstractMethod;


class AbstractMethodTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var AbstractMethod
     */
    protected $_method = null;


    public function setUp ()
    {
        $this->_method = $this->getMockForAbstractClass('Saml\Ecp\Authentication\Method\AbstractMethod');
    }


    public function testConstructor ()
    {
        $options = $this->_getOptions();
        
        $method = $this->getMockForAbstractClass('Saml\Ecp\Authentication\Method\AbstractMethod', array(
            $options
        ));
        
        $this->assertSame($options, (array) $method->getOptions());
    }


    public function testSetOptions ()
    {
        $options = $this->_getOptions();
        $this->_method->setOptions($options);
        
        $this->assertSame($options, (array) $this->_method->getOptions());
    }


    protected function _getOptions ()
    {
        return array(
            'foo1' => 'bar1', 
            'foo2' => 'bar2'
        );
    }
}