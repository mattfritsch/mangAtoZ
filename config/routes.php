<?php

use App\Controller\Admin;
use App\Controller\Homepage;
use \App\Controller\Login;
use \App\Controller\Connection;
use App\Controller\Product;
use App\Controller\Registration;
use App\Controller\Register;
use App\Controller\ChaptersPage;
use App\Controller\AddProductToCart;
use App\Controller\Panier;
use Framework\Routing\Route;

return [
    'routing' => [
        new Route('GET', '/', Homepage::class),
        new Route('GET', '/login', Login::class),
        new Route('POST', '/connection', Connection::class),
        new Route('POST', '/register', Register::class),
        new Route('GET', '/registration', Registration::class),
        new Route('GET', '/product', Product::class),
        new Route('GET', '/admin', Admin::class),
        new Route('GET', '/chapterspage', ChaptersPage::class),
        new Route('POST', '/chapterspage', ChaptersPage::class),
        new Route('POST', '/addproduct', AddProductToCart::class),
        new Route('GET', '/panier', Panier::class),
    ]
];
