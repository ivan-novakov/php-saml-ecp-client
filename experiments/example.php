<?php

use Saml\Ecp\Flow;
use Saml\Ecp\Client\Client;
use Saml\Ecp\Discovery\Method\StaticIdp;
use Saml\Ecp\Authentication\Method\BasicAuth;

$flow = new Flow\Basic();

$client = new Client(array(
    'http_client' => array(
        'options' => array(
            'cafile' => '/etc/ssl/certs/ca-bundle.pem'
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