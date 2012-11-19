<?php

namespace Saml\Ecp\Client;

use Zend\Http;
use Saml\Ecp\Exception as GeneralException;


class HttpClientFactory
{

    const OPT_OPTIONS = 'options';

    const OPT_ZEND_CLIENT_OPTIOS = 'zend_client_options';

    const OPT_CURL_ADAPTER_OPTIONS = 'curl_adapter_options';

    const OPT_CAFILE = 'cafile';

    const OPT_CAPATH = 'capath';

    protected $_defaultZendClientOptions = array(
        'maxredirects' => 0, 
        'strictredirects' => true, 
        'useragent' => 'PHP SAML ECP Client (https://github.com/ivan-novakov/php-saml-ecp-client)'
    );

    protected $_defaultCurlAdapterOptions = array(
        CURLOPT_SSL_VERIFYPEER => true, 
        CURLOPT_SSL_VERIFYHOST => 2
    );


    public function createHttpClient (array $config)
    {
        $zendClientOptions = $this->_defaultZendClientOptions;
        $curlAdapterOptions = $this->_defaultCurlAdapterOptions;
        
        if (! isset($config[self::OPT_OPTIONS]) || ! is_array($config[self::OPT_OPTIONS])) {
            throw new GeneralException\MissingConfigException(self::OPT_OPTIONS);
        }
        
        $options = $config[self::OPT_OPTIONS];
        
        if (isset($config[self::OPT_ZEND_CLIENT_OPTIOS]) && is_array($config[self::OPT_ZEND_CLIENT_OPTIOS])) {
            $zendClientOptions = $config[self::OPT_ZEND_CLIENT_OPTIOS] + $zendClientOptions;
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