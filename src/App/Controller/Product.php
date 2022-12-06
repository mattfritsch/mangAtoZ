<?php

namespace App\Controller;


use Framework\Response\Response;
use function App\getTextLangue;

class Product{
    public function __invoke()
    {

        $args = ['lang' => getTextLangue('en')];
        return new Response('productPage.html.twig', $args);
    }
}
