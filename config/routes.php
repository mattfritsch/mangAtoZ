<?php

use App\Controller\Admin;
use App\Controller\Homepage;
use \App\Controller\Login;
use \App\Controller\Connection;
use App\Controller\Registration;
use App\Controller\Register;
use Framework\Routing\Route;

return [
    'routing' => [
        new Route('GET', '/', Homepage::class),
        new Route('GET', '/login', Login::class),
        new Route('POST', '/connection', Connection::class),
        new Route('POST', '/register', Register::class),
        new Route('GET', '/registration', Registration::class),
        new Route('GET', '/admin', Admin::class),
    ]
];
