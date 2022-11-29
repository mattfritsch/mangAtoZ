<?php

use App\Controller\Homepage;
use \App\Controller\Login;
use App\Controller\Registration;
use Framework\Routing\Route;

return [
    'routing' => [
        new Route('GET', '/', Homepage::class),
        new Route('GET', '/login', Login::class),
        new Route('GET', '/registration', Registration::class),
    ]
];
