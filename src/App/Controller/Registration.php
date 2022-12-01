<?php
namespace App\Controller;

use Framework\Response\Response;
use function App\getTextLangue;

class Registration{
    public function __invoke()
    {
        if(isset($_SESSION["errors"])){
            $errors = $_SESSION["errors"];
        }
        else{
            $errors = "";
        }

        return new Response('registration.html.twig', ['lang' => getTextLangue('trad'), "errors" => $errors]);
    }
}

