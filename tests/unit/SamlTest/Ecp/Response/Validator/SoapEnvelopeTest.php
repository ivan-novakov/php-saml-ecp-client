<?php

namespace SamlTest\Ecp\Response\Validator;

use Saml\Ecp\Response\Validator\SoapEnvelope;


class SoapEnvelopeTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var SoapEnvelope
     */
    protected $_validator = null;


    public function setUp ()
    {
        $this->_validator = new SoapEnvelope(array(
            SoapEnvelope::OPT_SOAP_ENVELOPE_XSD => TESTS_FILES_DIR . 'schema/soap11-envelope.xsd'
        ));
    }


    public function testIsValidImplicit ()
    {
        $this->setExpectedException('Saml\Ecp\Exception\MissingOptionException');
        
        $validator = new SoapEnvelope();
        $validator->isValid($this->_getResponseMock());
    }


    public function testIsValidSchemaNotFound ()
    {
        $this->setExpectedException('Saml\Ecp\Exception\FileNotFoundException');
        
        $this->_validator->setOptions(array(
            SoapEnvelope::OPT_SOAP_ENVELOPE_XSD => '/nonexistent/file'
        ));
        
        $this->_validator->isValid($this->_getResponseMock());
    }


    public function testIsValidInvalidSchemaFile ()
    {
        $this->setExpectedException('Saml\Ecp\Exception\InvalidFileException');
        
        $this->_validator->setOptions(array(
            SoapEnvelope::OPT_SOAP_ENVELOPE_XSD => TESTS_FILES_DIR . 'schema/'
        ));
        
        $this->_validator->isValid($this->_getResponseMock());
    }
    
    /*
     * FIXME - testIsValidSchemaFileNotReadable
     */
    //
    public function testIsValidSoapLoadError ()
    {
        $response = $this->_getResponseMock();
        $response->expects($this->once())
            ->method('getSoapMessage')
            ->will($this->throwException(new \Exception()));
        
        $this->assertFalse($this->_validator->isValid($response));
    }


    public function testIsValidFailSchemaValidateException ()
    {
        $response = $this->_getResponseMock($this->throwException(new \Exception()));
        $this->assertFalse($this->_validator->isValid($response));
    }


    public function testIsValidFailSchemaValidateReturnFalse ()
    {
        $response = $this->_getResponseMock($this->returnValue(false));
        $this->assertFalse($this->_validator->isValid($response));
    }


    public function testIsValidSuccess ()
    {
        $response = $this->_getResponseMock($this->returnValue(true));
        $this->assertTrue($this->_validator->isValid($response));
    }


    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function _getResponseMock ($schemaValidateWill = null)
    {
        $response = $this->getMockBuilder('Saml\Ecp\Response\ResponseInterface')
            ->getMock();
        
        if (! $schemaValidateWill) {
            return $response;
        }
        
        $dom = $this->getMock('DomDocument');
        $dom->expects($this->once())
            ->method('schemaValidate')
            ->will($schemaValidateWill);
        
        $soapMessage = $this->getMockBuilder('Saml\Ecp\Soap\Message\Message')
            ->disableOriginalConstructor()
            ->getMock();
        $soapMessage->expects($this->once())
            ->method('getDom')
            ->will($this->returnValue($dom));
        
        $response->expects($this->once())
            ->method('getSoapMessage')
            ->will($this->returnValue($soapMessage));
        
        return $response;
    }
}