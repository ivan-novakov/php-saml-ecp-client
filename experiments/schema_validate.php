<?php

use Zend\Stdlib\ErrorHandler;

require __DIR__ . '/_common.php';

$xmlFile = '/tmp/soap-request-bad.xml';
$xsdFile = __DIR__ . '/../schema/soap11-envelope.xsd';


ErrorHandler::start();
$dom = new DOMDocument();
$dom->load($xmlFile);

$dom->schemaValidate($xsdFile);
ErrorHandler::stop(true);