<?php

use Saml\Ecp\Client\Exception\ResponseValidationException;

use Saml\Ecp\Client\Exception\InvalidResponseException;

use Saml\Ecp\Flow;
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

$flow = new Flow\Basic();
$flow->setClient($client);

try {
    $response = $flow->authenticate($globalConfig->get('protected_content_uri'), $discoveryMethod, $authenticationMethod);
} catch (ResponseValidationException $e) {
    _dump('Validation exception:');
    _dump("$e");
} catch (InvalidResponseException $e) {
    _dump('Invalid response:');
    _dump("$e");
} catch (\Exception $e) {
    _dump('General exception:');
    _dump("$e");
}