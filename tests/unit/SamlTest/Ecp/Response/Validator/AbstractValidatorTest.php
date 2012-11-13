<?php

namespace SamlTest\Ecp\Response\Validator;

use Saml\Ecp\Response\Validator\AbstractValidator;


class AbstractValidatorTest extends \PHPUnit_Framework_TestCase
{


    public function testConstructorNoOptions ()
    {
        $validator = $this->_getValidatorMock();
        
        $options = $validator->getOptions();
        $this->assertInstanceOf('Saml\Ecp\Util\Options', $options);
        $this->assertCount(0, $options);
    }


    public function testConstructorWithOptions ()
    {
        $validator = $this->_getValidatorMock(array(
            array(
                'foo1' => 'bar1'
            )
        ));
        
        $this->assertSame('bar1', $validator->getOption('foo1'));
    }


    public function testSetOptions ()
    {
        $validator = $this->_getValidatorMock(array(
            array(
                'foo1' => 'bar1'
            )
        ));
        
        $validator->setOptions(array(
            'foo1' => 'bar11', 
            'foo2' => 'bar2'
        ));
        
        $this->assertSame('bar11', $validator->getOption('foo1'));
        $this->assertSame('bar2', $validator->getOption('foo2'));
    }


    public function testGetMessagesEmtpy ()
    {
        $validator = $this->_getValidatorMock();
        
        $messages = $validator->getMessages();
        $this->assertInternalType('array', $messages);
        $this->assertEmpty($messages);
    }


    public function testAddMessage ()
    {
        $validator = $this->_getValidatorMock();
        $validator->addMessage('test message');
        
        $messages = $validator->getMessages();
        $this->assertCount(1, $messages);
        
        $expected = array(
            get_class($validator) => array(
                'test message'
            )
        );
        $this->assertSame($expected, $messages);
    }


    protected function _getValidatorMock ($arguments = array())
    {
        return $this->getMockForAbstractClass('Saml\Ecp\Response\Validator\AbstractValidator', $arguments);
    }
}
