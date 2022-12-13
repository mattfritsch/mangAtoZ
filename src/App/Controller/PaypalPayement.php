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
        $info = $_SESSION['prixtotal'];
        $args = ['lang' => getTextLangue($_SESSION['locale']), 'info' =>$info];
        return new Response('pagepayement.html.twig', $args );

    }
}