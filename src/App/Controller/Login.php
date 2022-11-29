<?php

namespace App\Controller;

use Framework\Response\Response;
use function App\getTextLangue;

class Login{
    public function __invoke()
    {

        $args = ['lang' => getTextLangue('en')];
        return new Response('login.html.twig', $args);
    }
}
