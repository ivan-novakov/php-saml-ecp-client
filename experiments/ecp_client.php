<?php

use Saml\Ecp\Client\Client;
use Saml\Ecp\Discovery\Method\StaticIdp;
use Saml\Ecp\Authentication\Method\BasicAuth;

require __DIR__ . '/_common.php';

$credentials = $globalConfig->get('credentials');
$authenticationMethod = new BasicAuth($credentials->toArray());

$discoveryOptions = $globalConfig->get('discovery')
    ->get('options');
$discoveryMethod = new StaticIdp($discoveryOptions->toArray());

$client = new Client($globalConfig->get('client'));
$response = $client->authenticate($authenticationMethod, $discoveryMethod);

_dump((string) $response->getHttpResponse());

