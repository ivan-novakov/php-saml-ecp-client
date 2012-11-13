<?php

namespace Saml\Ecp\Response\Validator;

use Zend\Stdlib\ErrorHandler;
use Saml\Ecp\Response\ResponseInterface;
use Saml\Ecp\Exception as GeneralException;


/**
 * Validates that the response contains a valid SOAP envelope.
 *
 */
class SoapEnvelope extends AbstractValidator
{

    const OPT_SOAP_ENVELOPE_XSD = 'soap_envelope_xsd';


    /**
     * (non-PHPdoc)
     * @see \Saml\Ecp\Response\Validator\ValidatorInterface::isValid()
     */
    public function isValid (ResponseInterface $response)
    {
        $valid = false;
        
        $schemaFile = $this->getOption(self::OPT_SOAP_ENVELOPE_XSD);
        if (null === $schemaFile) {
            throw new GeneralException\MissingOptionException(self::OPT_SOAP_ENVELOPE_XSD);
        }
        
        if (! file_exists($schemaFile)) {
            throw new GeneralException\FileNotFoundException(sprintf("File not found: '%s'", $schemaFile));
        }
        
        if (! is_file($schemaFile)) {
            throw new GeneralException\InvalidFileException(sprintf("Invalid file: '%s'", $schemaFile));
        }
        
        if (! is_readable($schemaFile)) {
            throw new GeneralException\InvalidFileException(sprintf("File not readable: '%s'", $schemaFile));
        }
        
        try {
            $soapMessage = $response->getSoapMessage();
        } catch (\Exception $e) {
            $this->addMessage(sprintf("Error loading SOAP message: [%s] %s", get_class($e), $e->getMessage()));
            return false;
        }
        
        $dom = $soapMessage->getDom();
        
        try {
            ErrorHandler::start();
            if ($dom->schemaValidate($schemaFile)) {
                $valid = true;
            }
            ErrorHandler::stop(true);
        } catch (\Exception $e) {
            $this->addMessage(sprintf("Failed schema validate (schema:%s): [%s] %s", $schemaFile, get_class($e), $e->getMessage()));
        }
        
        if (ErrorHandler::started()) {
            ErrorHandler::stop();
        }
        
        return $valid;
    }
}