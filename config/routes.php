<?php

use App\Controller\AdminCategs;
use App\Controller\AdminOrders;
use App\Controller\AdminProducts;
use App\Controller\AdminProduct;
use App\Controller\AdminChapters;
use App\Controller\CategoriesPage;
use App\Controller\Homepage;
use \App\Controller\Login;
use App\Controller\ProductPage;
use App\Controller\Registration;
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
        new Route('GET', '/product', ProductPage::class),
        new Route('POST', '/product', ProductPage::class),
        new Route('GET', '/admin/products', AdminProducts::class),
        new Route('POST', '/admin/products', AdminProducts::class),
        new Route('GET', '/store', ProductPage::class),
        new Route('POST', '/store', ProductPage::class),
        new Route('GET', '/categories', CategoriesPage::class),
        new Route('POST', '/addproduct', AddProductToCart::class),
        new Route('GET', '/admin/users', AdminUsers::class),
        new Route('POST', '/admin/users', AdminUsers::class),
        new Route('GET', '/admin/product', AdminProduct::class),
        new Route('POST', '/admin/product', AdminProduct::class),
        new Route('GET', '/admin/chapters', AdminChapters::class),
        new Route('POST', '/admin/chapters', AdminChapters::class),
        new Route('GET', '/admin/orders', AdminOrders::class),
        new Route('POST', '/admin/orders', AdminOrders::class),
        new Route('GET', '/admin/categs', AdminCategs::class),
        new Route('POST', '/admin/categs', AdminCategs::class),
        new Route('GET', '/chapterspage', ChaptersPage::class),
        new Route('POST', '/chapterspage', ChaptersPage::class),
        new Route('GET', '/panier', Panier::class),
    ]
];
