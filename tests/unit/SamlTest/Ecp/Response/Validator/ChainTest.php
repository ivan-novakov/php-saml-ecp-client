<?php

namespace SamlTest\Ecp\Response\Validator;

use Saml\Ecp\Response\Validator\Chain;


class ChainTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Chain
     */
    protected $_chain = null;


    public function setUp ()
    {
        $this->_chain = new Chain();
    }


    public function testAddValidator ()
    {
        $validator1 = $this->getMock('Saml\Ecp\Response\Validator\ValidatorInterface');
        $validator2 = $this->getMock('Saml\Ecp\Response\Validator\ValidatorInterface');
        $this->_chain->addValidator($validator1);
        $this->_chain->addValidator($validator2);
        $validators = $this->_chain->getValidators();
        
        $this->assertSame($validator1, $validators[0]);
        $this->assertSame($validator2, $validators[1]);
    }


    public function testIsValidFalse ()
    {
        $response = $this->getMock('Saml\Ecp\Response\ResponseInterface');
        
        $validator1 = $this->getMock('Saml\Ecp\Response\Validator\ValidatorInterface');
        $validator1->expects($this->once())
            ->method('isValid')
            ->with($response)
            ->will($this->returnValue(true));
        
        $validator2 = $this->getMock('Saml\Ecp\Response\Validator\ValidatorInterface');
        $validator2->expects($this->once())
            ->method('isValid')
            ->with($response)
            ->will($this->returnValue(false));
        $validator2->expects($this->once())
            ->method('getMessages')
            ->will($this->returnValue(array(
            'message 1', 
            'message 2'
        )));
        
        $this->_chain->addValidator($validator1);
        $this->_chain->addValidator($validator2);
        
        $this->assertFalse($this->_chain->isValid($response));
        
        $messages = $this->_chain->getMessages();
        $expectedMessages = array(
            sprintf("[%s] message 1", get_class($validator2)), 
            sprintf("[%s] message 2", get_class($validator2))
        );
        
        $this->assertSame($expectedMessages, $messages);
    }


    public function testIsValidTrue ()
    {
        $response = $this->getMock('Saml\Ecp\Response\ResponseInterface');
        
        $validator1 = $this->getMock('Saml\Ecp\Response\Validator\ValidatorInterface');
        $validator1->expects($this->once())
            ->method('isValid')
            ->with($response)
            ->will($this->returnValue(true));
        
        $validator2 = $this->getMock('Saml\Ecp\Response\Validator\ValidatorInterface');
        $validator2->expects($this->once())
            ->method('isValid')
            ->with($response)
            ->will($this->returnValue(true));
        
        $this->_chain->addValidator($validator1);
        $this->_chain->addValidator($validator2);
        
        $this->assertTrue($this->_chain->isValid($response));
    }
}