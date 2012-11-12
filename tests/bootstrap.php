<?php

require __DIR__ . '/../vendor/autoload.php';

define('TESTS_FILES_DIR', __DIR__ . '/files/');

//--
function _dump ($value)
{
    error_log(print_r($value, true)) . "\n";
}