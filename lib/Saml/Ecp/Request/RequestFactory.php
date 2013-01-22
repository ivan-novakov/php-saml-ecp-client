<?php

namespace Saml\Ecp\Request;

use Saml\Ecp\Soap\Container\BodyCopier;
use Saml\Ecp\Soap\Container\ContainerInterface;


/**
 * Request factory class.
 * 
 * @copyright (c) 2013 Ivan Novakov (http://novakov.cz/)
 * @license http://debug.cz/license/freebsd
 */
class RequestFactory implements RequestFactoryInterface
{

    /**
     * The SOAP body copier object.
     * 
     * @var BodyCopier
     */
    protected $_soapBodyCopier = null;


    /**
     * Returns the SOAP body copier object.
     * 
     * @return BodyCopier
     */
    public function getSoapBodyCopier()
    {
        if (! ($this->_soapBodyCopier instanceof BodyCopier)) {
            $this->_soapBodyCopier = new BodyCopier();
        }
        
        return $this->_soapBodyCopier;
    }


    /**
     * Sets the SOAP body copier object.
     * 
     * @param BodyCopier $soapBodyCopier
     */
    public function setSoapBodyCopier(BodyCopier $soapBodyCopier)
    {
        $this->_soapBodyCopier = $soapBodyCopier;
    }


    /**
     * {@inheritdoc}
     * @see \Saml\Ecp\Request\RequestFactoryInterface::createSpInitialRequest()
     * @return SpInitialRequest
     */
    public function createSpInitialRequest($protectedContentUri)
    {
        $request = new SpInitialRequest();
        $request->setUri($protectedContentUri);
        
        return $request;
    }


    /**
     * {@inheritdoc}
     * @see \Saml\Ecp\Request\RequestFactoryInterface::createIdpAuthnRequest()
     * @return IdpAuthnRequest
     */
    public function createIdpAuthnRequest(ContainerInterface $soapContainer, $idpEndpointUrl)
    {
        $request = new IdpAuthnRequest();
        $this->getSoapBodyCopier()
            ->copyBody($soapContainer, $request);
        $request->setUri($idpEndpointUrl);
        
        return $request;
    }


    /**
     * {@inheritdoc}
     * @see \Saml\Ecp\Request\RequestFactoryInterface::createSpAuthnConveyRequest()
     * @return SpConveyAuthnRequest
     */
    public function createSpAuthnConveyRequest(ContainerInterface $soapContainer, $spEndpointUrl)
    {
        $request = new SpConveyAuthnRequest();
        $this->getSoapBodyCopier()
            ->copyBody($soapContainer, $request);
        $request->setUri($spEndpointUrl);
        
        return $request;
    }


    /**
     * {@inheritdoc}
     * @see \Saml\Ecp\Request\RequestFactoryInterface::createSpResourceRequest()
     * @return SpResourceRequest
     */
    public function createSpResourceRequest($resourceUri)
    {
        $request = new SpResourceRequest();
        $request->setUri($resourceUri);
        
        return $request;
    }
}