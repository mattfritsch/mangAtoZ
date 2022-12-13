<?php

use App\Controller\Admin;
use App\Controller\Homepage;
use \App\Controller\Login;
use \App\Controller\Connection;
use App\Controller\ProductPage;
use App\Controller\Registration;
use App\Controller\Register;
use Framework\Routing\Route;
use App\Controller\AdminUsers;
use App\Controller\ChaptersPage;
use App\Controller\AddProductToCart;
use App\Controller\Panier;
use App\Controller\ModifyCart;
use App\Controller\PaypalPayement;


return [
    'routing' => [
        new Route('GET', '/', Homepage::class),
        new Route('GET', '/login', Login::class),
        new Route('POST', '/connection', Connection::class),
        new Route('POST', '/register', Register::class),
        new Route('GET', '/registration', Registration::class),
        new Route('GET', '/product', ProductPage::class),
        new Route('POST', '/product', ProductPage::class),
        new Route('GET', '/admin', Admin::class),
        new Route('POST', '/addproduct', AddProductToCart::class),
        new Route('GET', '/admin/users', AdminUsers::class),
        new Route('POST', '/admin/users', AdminUsers::class),
        new Route('GET', '/chapterspage', ChaptersPage::class),
        new Route('POST', '/chapterspage', ChaptersPage::class),
        new Route('POST', '/modifycart', ModifyCart::class),
        new Route('GET', '/panier', Panier::class),
        new Route('GET', '/payement', PaypalPayement::class),

    ]
];
