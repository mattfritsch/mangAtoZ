<?php
namespace App\Controller;

use Framework\Response\Response;
use function App\getTextLangue;

class Registration{
    public function __invoke()
    {

        return new Response('registration.html.twig', ['lang' => getTextLangue('trad'), "" => $_SESSION["errors"]]);
    }
}

