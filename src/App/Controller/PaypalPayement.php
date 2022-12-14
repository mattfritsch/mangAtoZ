<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Framework\Doctrine\EntityManager;
use Framework\Response\Response;
use function App\getTextLangue;
use function App\isUser;
use function App\startSession;

class PaypalPayement{
    public function __invoke()
    {
        startSession();
        var_dump($_SESSION);
        $info = $_SESSION['prixtotal'];
        var_dump($info);
        $args = ['lang' => getTextLangue($_SESSION['locale']), 'info' =>$info];
        return new Response('pagepayement.html.twig', $args );

    }
}