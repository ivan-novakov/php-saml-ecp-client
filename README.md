# PHP SAML ECP Client

[![Dependency Status](https://www.versioneye.com/user/projects/529a014c632bac5a0a000018/badge.png)](https://www.versioneye.com/user/projects/529a014c632bac5a0a000018)

## Introduction

As described in the current [specification draft](https://wiki.oasis-open.org/security/SAML2EnhancedClientProfile), the SAML V2.0 Enhanced Client or Proxy (ECP) profile is a SSO profile for use with HTTP, and clients with the capability to directly contact a principal's identity provider(s) without requiring discovery and redirection by the service provider, as in the case of a browser. It is particularly useful for desktop or server-side HTTP clients.

This library tries to follow the ECP profile specification. Currently, it doesn't support the „Holder of Key“ and „Channel Bindings“ features. The status of the library is „highly experimental“. It is not 100% ready and it hasn't been tested in different environments.

## Requirements

* PHP >= 5.3
* Zend Framework >= 2.*
* Shibboleth SP/IdP

## Installation

If you use [composer](http://getcomposer.org/) in your project, you can just add the following requirement to your `composer.json` file:

    "ivan-novakov/php-saml-ecp-client": "dev-master"
    
Otherwise, clone the repository and configure your autoloader to look for the _Saml_ namespace in the `lib/` directory of the repository.

## Basic usage

    use Saml\Ecp\Flow;
    use Saml\Ecp\Client\Client;
    use Saml\Ecp\Discovery\Method\StaticIdp;
    use Saml\Ecp\Authentication\Method\BasicAuth;

    $flow = new Flow\Basic();
 
    $client = new Client(array( 
        'http_client' => array(
            'options' => array(
                'cafile' => '/etc/ssl/certs/tcs-ca-bundle.pem'
            )
        )
    ));
    $flow->setClient($client);
 
    $authenticationMethod = new BasicAuth(array(
        'username' => 'user', 
        'password' => 'passwd' 
    ));
 
    $discoveryMethod = new StaticIdp(array(
        'idp_ecp_endpoint' => 'https://idp.example.org/idp/profile/SAML2/SOAP/ECP'
    ));
 
    $response = $flow->authenticate('https://sp.example.com/secure', $discoveryMethod, $authenticationMethod);

The Client object is responsible for the actual work - sending requests and validating responses. The Flow object uses the client object to issue requests in the apropriate order. The authenticate() method performs the whole ECP flow, when the client tries to access the protected resource and then it is redirected to the IdP for authentication. Besides the resource URL, the authenticate() method needs a discovery method object, which determines the IdP to use for authentication and an authentication method object, which adjusts the authentication request.

In this case the discovery method ( _StaticIdP_ ) just returns the IdP endpoint. The authentication method ( _BasicAuth_ ) adjusts the request to perform a HTTP Basic authentication based on the provided credentials.

## Shibboleth SP configuration

Shibboleth SP supports the ECP profile, but it needs to be „switched on“ in the [SessionInitiator configuration](https://wiki.shibboleth.net/confluence/display/SHIB2/NativeSPSessionInitiator#NativeSPSessionInitiator-SAML2SessionInitiatorProtocolHandler):

    <SessionInitiator id="ECP" type="SAML2" Location="/ECP" ECP="true" 
        entityID="https://idp.example.org/idp/shibboleth">
    </SessionInitiator>

In case this is not the default session initiator (as the above example), you need to configure Apache to use the right session initiator for the secured resource:

    <Location /secure>
        AuthType shibboleth  
        ShibRequestSetting requireSessionWith ECP
        Require valid-user
    </Location>

## Shibboleth IdP configuration

The IdP supports the ECP profile ["out of the box"](https://wiki.shibboleth.net/confluence/display/SHIB2/IdPEnableECP). Currently the ECP profile handler requires external web server based authentication. Basically, it means thet you need to protect the ECP profile handler endpoint with some kind of HTTP Basic authentication in the same way as in case of using the RemoteUser login handler.

    <Location /idp/profile/SAML2/SOAP/ECP>
        AuthType Basic
        AuthName "IdP ECP endpoint authentication"
        AuthBasicProvider ldap
        AuthLDAPURL "ldap://127.0.0.1/o=example.org"
        AuthzLDAPAuthoritative off
        require valid-user
    </Location>

## Advanced usage

This library is more a framework than a ready to use application. There are numerous environments and use cases and it's not possible to cover them all „out of the box“. That is why the library has been designed to be as flexible and extensible as possible. Some parts may be easily exchanged with alternative implementations or extended with additional features.

### The HTTP client

The _Saml\Ecp\Client\Client_ object uses internally the _Zend\Http\Client_ object with the cURL adapter ( _Zend\Http\Client\Adapter\Curl_ ). For security reasons the peer and host validation is on by default (`CURLOPT_SSL_VERIFYPEER = true`, `CURLOPT_SSL_VERIFYHOST = 2`). You have to pass one of the following options:

* **cafile** - path to the file containing CA certificates used for peer/host validation
* **capath** - path the the directory contiaining CA certificates used for peer/host validation

You can also pass options directly to the HTTP client and the cURL adapter through these options:

* **zend_client_options** - array of options as described in [ZF2 manual](https://packages.zendframework.com/docs/latest/manual/en/modules/zend.http.client.html)
* **curl_adapter_options** - array of options as described in [ZF2 manual](https://packages.zendframework.com/docs/latest/manual/en/modules/zend.http.client.adapters.html#the-curl-adapter)

Example:

    $client = new \Saml\Ecp\Client\Client(array(
        'http_client' => array(
            'options' => array(
                'cafile' => '/etc/ssl/certs/ca-bundle.crt'
            ),
            'zend_client_options' => array(
                'useragent' => 'My ECP Client v0.1'
            ),
            'curl_adapter_options' => array(
                CURLOPT_FORBID_REUSE => true
            )
        )
    ));

The Client object uses the _Saml\Ecp\Client\HttpClientFactory_ to create the HTTP client object bases on the „http_client“ option. Instead of passing the „http_client“ option to the Client object, you can explicitly create the _Zend\Http\Client_ object and inject it:

    $httpClient = new \Zend\Http\Client();
    $httpClient->setOptions(array(
        // options
    ));
 
    $adapter = new \Zend\Http\Client\Adapter\Curl();
    $adapter->setOptions(array(
        // options
    ));
 
    $httpClient->setAdapter($adapter);
 
    $client = new \Saml\Ecp\Client\Client();
    $client->setHttpClient($httpClient);

### Discovery method

You can write your own IdP discovery method by implementing the _Saml\Ecp\Discovery\Method\MethodInterface_.

### Authentication method

You can code another authentication method by implementing the _Saml\Ecp\Authentication\Method\MethodInterface_.

### Requests

If you need to implement alternative request objects, you can extend the _Saml\Ecp\Request\AbstractRequest_ object or just implement the _Saml\Ecp\Request\RequestInterface_. You will also have to implement your own request factory by implementing the _Saml\Ecp\Request\RequestFactoryInterface_ and inject it into the _Saml\Ecp\Client\Client_ object so the client can use your alternative request objects instead of the „standard“ ones.

### Responses

Similar to the requests, you can write your own by extending the abstract response class ( _Saml\Ecp\Response\AbstractResponse_ ) or by implementing the response interface ( _Saml\Ecp\Response\ResponseInterface_ ). Additionaly you need to write an alternative response factory implementing the _Saml\Ecp\Response\ResponseFactoryInterface_.

### Response validation

Response validation is achieved through validators created by the _Saml\Ecp\Response\Validator\ValidatorFactory_. The validators must implement the _Saml\Ecp\Response\Validator\ValidatorInterface_ and the validator factory must implement the _Saml\Ecp\Response\Validator\ValidatorFactoryInterface_. The validator factory has to be injected into the client object ( _Saml\Ecp\Client\Client_ ).

## Licence

* [BSD 3 Clause](http://debug.cz/license/bsd-3-clause)

## Links

* [API docs](http://debug.cz/apidocs/ecp/)

## Specifications

* https://wiki.oasis-open.org/security/SAML2EnhancedClientProfile
* http://docs.oasis-open.org/security/saml/v2.0/saml-core-2.0-os.pdf
* http://docs.oasis-open.org/security/saml/v2.0/saml-metadata-2.0-os.pdf
* http://www.projectliberty.org/liberty/content/download/1219/7957/file/liberty-paos-v1.1.pdf
* http://docs.oasis-open.org/security/saml/Post2.0/sstc-saml-channel-binding-ext.pdf
