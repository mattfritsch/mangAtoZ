<?php

namespace App\Controller;


use App\Entity\CartProduct;
use App\Entity\Chapter;
use App\Repository\ChaptersRepository;
use Framework\Doctrine\EntityManager;
use Framework\Response\Response;
use function App\getTextLangue;



class Panier{
    public function __invoke()
    {



        $args = ['lang' => getTextLangue('en')];
        return new Response('panier.html.twig', $args);

    }
}