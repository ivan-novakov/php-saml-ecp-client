<?php

namespace Saml\Ecp\Client;

use Zend\Http;
use Saml\Ecp\Exception as GeneralException;


/**
 * Factory class for creating Zend HTTP client objects.
 * 
 * <code>
 * $httpClientFactory = new HttpClientFactory();
 * $httpClient = $httpClientFactory->createHttpClient(array(
 *     'options' => array(
 *         'cafile' => '/etc/ssl/certs/tcs-ca-bundle.pem'
 *     )
 * ));
 * </code>
 * 
 * @copyright (c) 2013 Ivan Novakov (http://novakov.cz/)
 * @license http://debug.cz/license/freebsd
 */
class HttpClientFactory
{

    /**
     * Option index.
     */
    const OPT_OPTIONS = 'options';

    /**
     * Option index.
     */
    const OPT_ZEND_CLIENT_OPTIONS = 'zend_client_options';

    /**
     * Option index.
     */
    const OPT_CURL_ADAPTER_OPTIONS = 'curl_adapter_options';

    /**
     * Option index.
     */
    const OPT_CAFILE = 'cafile';

    /**
     * Option index.
     */
    const OPT_CAPATH = 'capath';

    /**
     * Default Zend\Http\Client options.
     * 
     * @see https://packages.zendframework.com/docs/latest/manual/en/modules/zend.http.client.html
     * @var array
     */
    protected $_defaultZendClientOptions = array(
        'maxredirects' => 0, 
        'strictredirects' => true, 
        'useragent' => 'PHP SAML ECP Client (https://github.com/ivan-novakov/php-saml-ecp-client)'
    );

    /**
     * Default cURL adapter options.
     * 
     * @see https://packages.zendframework.com/docs/latest/manual/en/modules/zend.http.client.adapters.html#the-curl-adapter
     * @var array
     */
    protected $_defaultCurlAdapterOptions = array(
        CURLOPT_SSL_VERIFYPEER => true, 
        CURLOPT_SSL_VERIFYHOST => 2
    );


    /**
     * Creates a Zend\Http\Client object based on the provided configuration.
     * 
     * @param array $config
     * @throws GeneralException\MissingConfigException
     * @return \Zend\Http\Client
     */
    public function createHttpClient(array $config)
    {
        $zendClientOptions = $this->_defaultZendClientOptions;
        $curlAdapterOptions = $this->_defaultCurlAdapterOptions;
        
        if (! isset($config[self::OPT_OPTIONS]) || ! is_array($config[self::OPT_OPTIONS])) {
            throw new GeneralException\MissingConfigException(self::OPT_OPTIONS);
        }
        
        $options = $config[self::OPT_OPTIONS];
        
        if (isset($config[self::OPT_ZEND_CLIENT_OPTIONS]) && is_array($config[self::OPT_ZEND_CLIENT_OPTIONS])) {
            $zendClientOptions = $config[self::OPT_ZEND_CLIENT_OPTIONS] + $zendClientOptions;
        }
        
        if (isset($config[self::OPT_CURL_ADAPTER_OPTIONS]) && is_array($config[self::OPT_CURL_ADAPTER_OPTIONS])) {
            $curlAdapterOptions = $config[self::OPT_CURL_ADAPTER_OPTIONS] + $curlAdapterOptions;
        }
        
        if (! isset($options[self::OPT_CAFILE]) && ! isset($options[self::OPT_CAPATH])) {
            throw new GeneralException\MissingConfigException(sprintf("%s/%s or %s/%s", self::OPT_OPTIONS, self::OPT_CAFILE, self::OPT_OPTIONS, self::OPT_CAPATH));
        }
        
        if (isset($options[self::OPT_CAFILE])) {
            $curlAdapterOptions[CURLOPT_CAINFO] = $options[self::OPT_CAFILE];
        }
        
        if (isset($options[self::OPT_CAPATH])) {
            $curlAdapterOptions[CURLOPT_CAPATH] = $options[self::OPT_CAPATH];
        }
        
        $client = new Http\Client();
        $client->setOptions($zendClientOptions);
        
        $adapter = new Http\Client\Adapter\Curl();
        $adapter->setOptions(array(
            'curloptions' => $curlAdapterOptions
        ));
        $client->setAdapter($adapter);
        
        return $client;
    }
}