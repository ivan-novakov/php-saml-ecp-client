<?php

namespace Saml\Ecp\Response;

use Saml\Ecp\Soap\Message;
use Saml\Ecp\Util\Options;
use Zend\Http;


abstract class AbstractResponse implements ResponseInterface
{

    /**
     * HTTP response.
     * 
     * @var Http\Response
     */
    protected $_httpResponse = null;

    /**
     * The SOAP message contained in the response.
     * 
     * @var Message
     */
    protected $_soapMessage = null;

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
    public function __construct (Http\Response $httpResponse, $options = array())
    {
        $this->setOptions($options);
        $this->setHttpResponse($httpResponse);
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
     * (non-PHPdoc)
     * @see \Saml\Ecp\Response\ResponseInterface::setHttpResponse()
     */
    public function setHttpResponse (Http\Response $httpResponse)
    {
        $this->_httpResponse = $httpResponse;
    }


    /**
     * Returns the HTTP response.
     * 
     * @return Http\Response
     */
    public function getHttpResponse ()
    {
        return $this->_httpResponse;
    }


    /**
     * (non-PHPdoc)
     * @see \Saml\Ecp\Response\ResponseInterface::getContent()
     */
    public function getContent ()
    {
        return $this->getHttpResponse()
            ->getBody();
    }


    /**
     * (non-PHPdoc)
     * @see \Saml\Ecp\Response\ResponseInterface::validate()
     */
    public function validate (array $validateOptions = array())
    {}


    /**
     * (non-PHPdoc)
     * @see \Saml\Ecp\Soap\ContainerInterface::getSoapMessage()
     * @return Message
     */
    public function getSoapMessage ()
    {
        if (! ($this->_soapMessage instanceof Message)) {
            $this->_soapMessage = new Message($this->getContent());
        }
        
        return $this->_soapMessage;
    }


    /**
     * \(non-PHPdoc)
     * @see \Saml\Ecp\Soap\ContainerInterface::setSoapMessage()
     */
    public function setSoapMessage (Message $soapMessage)
    {
        $this->_soapMessage = $soapMessage;
    }


    protected function _validateStatusCode ()
    {
        $httpResponse = $this->getHttpResponse();
        
        if (200 != $httpResponse->getStatusCode()) {
            throw new Exception\BadResponseStatusException($httpResponse->getStatusCode());
        }
    }
}