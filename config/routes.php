<?php

use App\Controller\Homepage;
use \App\Controller\Login;
use Framework\Routing\Route;

return [
    'routing' => [
        new Route('GET', '/', Homepage::class),
        new Route('GET', '/login', Login::class),
    ]
];
