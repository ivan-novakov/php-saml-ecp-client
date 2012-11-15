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

$logger = new Zend\Log\Logger();
$writer = new Zend\Log\Writer\Stream($globalConfig->get('logger')
    ->get('file'));
$filter = new Zend\Log\Filter\Priority($globalConfig->get('logger')
    ->get('priority'));
$writer->addFilter($filter);
$logger->addWriter($writer);

$client = new Client($globalConfig->get('client'));
$client->setLogger($logger);

$response = $client->authenticate($authenticationMethod, $discoveryMethod);


