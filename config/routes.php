<?php

use App\Controller\Admin;
use App\Controller\AdminProduct;
use App\Controller\CategoriesPage;
use App\Controller\Homepage;
use App\Controller\Language;
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


return [
    'routing' => [
        new Route('GET', '/', Homepage::class),
        new Route('GET', '/login', Login::class),
        new Route('POST', '/login', Login::class),
        new Route('POST', '/registration', Registration::class),
        new Route('GET', '/registration', Registration::class),
        new Route('GET', '/store', ProductPage::class),
        new Route('POST', '/store', ProductPage::class),
        new Route('GET', '/categories', CategoriesPage::class),
        new Route('GET', '/admin', Admin::class),
        new Route('POST', '/admin', Admin::class),
        new Route('POST', '/addproduct', AddProductToCart::class),
        new Route('GET', '/admin/users', AdminUsers::class),
        new Route('POST', '/admin/users', AdminUsers::class),
        new Route('GET', '/admin/product', AdminProduct::class),
        new Route('POST', '/admin/product', AdminProduct::class),
        new Route('GET', '/chapterspage', ChaptersPage::class),
        new Route('POST', '/chapterspage', ChaptersPage::class),
        new Route('GET', '/panier', Panier::class),
        new Route('POST', '/language', Language::class),
        new Route('GET', '/language', Language::class),
    ]
];
