<?php

use App\Controller\Homepage;
use \App\Controller\Login;
use \App\Controller\Connection;
use Framework\Routing\Route;

return [
    'routing' => [
        new Route('GET', '/', Homepage::class),
        new Route('GET', '/login', Login::class),
        new Route('POST', '/connection', Connection::class),
    ]
];
