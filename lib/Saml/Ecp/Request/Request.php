<?php

namespace Saml\Ecp\Request;

use Saml\Ecp\Soap\Message;
use Saml\Ecp\Util\Options;
use Zend\Http;


class Request implements RequestInterface
{

    /**
     * HTTP request.
     * 
     * @var Http\Request
     */
    protected $_httpRequest = null;

    /**
     * The SOAP message to be sent.
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
    public function __construct ($options = array())
    {
        $this->setOptions($options);
        $this->_init();
    }


    public function setOptions ($options)
    {
        $this->_options = new Options($options);
    }


    /**
     * (non-PHPdoc)
     * @see \Saml\Ecp\Request\RequestInterface::setUri()
     */
    public function setUri ($uri)
    {
        $this->getHttpRequest()
            ->setUri($uri);
    }


    public function setHttpRequest (Http\Request $request)
    {
        $this->_httpRequest = $request;
    }


    /**
     * (non-PHPdoc)
     * @see \Saml\Ecp\Request\RequestInterface::getHttpRequest()
     */
    public function getHttpRequest ()
    {
        if (! ($this->_httpRequest instanceof Http\Request)) {
            $this->_httpRequest = new Http\Request();
        }
        
        return $this->_httpRequest;
    }


    /**
     * Sets a HTTP request header.
     * @param string $name
     * @param string $value
     */
    public function setHeader ($name, $value)
    {
        $this->getHttpRequest()
            ->getHeaders()
            ->addHeaders(array(
            $name => $value
        ));
    }


    /**
     * Sets the content of the request body.
     * 
     * @param string $content
     */
    public function setContent ($content)
    {
        $this->getHttpRequest()
            ->setContent($content);
    }


    /**
     * (non-PHPdoc)
     * @see \Saml\Ecp\Request\RequestInterface::setSoapMessage()
     */
    public function setSoapMessage (Message $message)
    {
        $this->_soapMessage = $message;
        $this->setContent($this->_soapMessage->toString());
    }


    public function getSoapMessage ()
    {
        return $this->_soapMessage;
    }


    protected function _init ()
    {}
}