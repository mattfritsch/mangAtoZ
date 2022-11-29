<?php
namespace App\Controller;

use Framework\Response\Response;
use function App\getTextLangue;

class Registration{
    public function __invoke()
    {

        $args = ['lang' => getTextLangue('en')];
        return new Response('registration.html.twig', $args);
    }
}

