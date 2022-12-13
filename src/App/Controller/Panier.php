<?php

namespace App\Controller;


use App\Entity\CartProduct;
use App\Entity\Chapter;
use App\Repository\ChaptersRepository;
use Framework\Doctrine\EntityManager;
use Framework\Response\Response;
use function App\getTextLangue;
use function App\isUser;


class Panier{
    public function __invoke()
    {



        $args = ['lang' => getTextLangue('en'), 'user' => isUser()];
        return new Response('panier.html.twig', $args);

    }
}