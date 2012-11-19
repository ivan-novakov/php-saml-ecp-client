<?php

namespace Saml\Ecp\Response\Validator;

use Saml\Ecp\Util\Options;
use Saml\Ecp\Client\MimeType;


/**
 * Factory class for creating response validators.
 *
 */
class ValidatorFactory implements ValidatorFactoryInterface
{
    
    /*
     * Options that may be passed to the constructor.
     */
    /**
     * The path to the SOAP envelope spec.
     * 
     * @var string
     */
    const OPT_SOAP_ENVELOPE_XSD = 'soap_envelope_xsd';
    
    /*
     * Options that may be passed to a factory method.
     */
    /**
     * The assertion consumer URL declared by the SP.
     * 
     * @var string
     */
    const CALLOPT_SP_ASSERTION_CONSUMER_URL = 'sp_assertion_consumer_url';

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


    /**
     * Returns the option value for the provided option name.
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
     * (non-PHPdoc)
     * @see \Saml\Ecp\Response\Validator\ValidatorFactoryInterface::createSpInitialResponseValidator()
     */
    public function createSpInitialResponseValidator (array $options = array())
    {
        $chainValidator = new Chain();
        
        $chainValidator->addValidator(new HttpStatus());
        $chainValidator->addValidator(new ContentType(array(
            ContentType::OPT_EXPECTED_CONTENT_TYPE => MimeType::PAOS
        )));
        $chainValidator->addValidator(new SoapEnvelope(array(
            SoapEnvelope::OPT_SOAP_ENVELOPE_XSD => $this->getOption(self::OPT_SOAP_ENVELOPE_XSD)
        )));
        $chainValidator->addValidator(new SoapHeaderActor());
        $chainValidator->addValidator(new SamlAuthnRequest());
        $chainValidator->addValidator(new PaosRequest());
        
        return $chainValidator;
    }


    /**
     * (non-PHPdoc)
     * @see \Saml\Ecp\Response\Validator\ValidatorFactoryInterface::createIdpAuthnResponseValidator()
     */
    public function createIdpAuthnResponseValidator (array $options = array())
    {
        $chainValidator = new Chain();
        
        $chainValidator->addValidator(new HttpStatus());
        $chainValidator->addValidator(new SamlAuthnResponse());
        
        return $chainValidator;
    }
}