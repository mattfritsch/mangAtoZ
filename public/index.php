<?php

use Framework\Kernel\Kernel;
use Framework\Request\Request;
use Framework\Templating\Twig;

require '../vendor/autoload.php';

$kernel = new Kernel(new Twig());
$response = $kernel->handleRequest(Request::fromGlobals());
var_dump($response);

$kernel->display($response);

