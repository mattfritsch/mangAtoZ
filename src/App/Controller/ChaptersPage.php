<?php

namespace App\Controller;


use App\Entity\Chapter;
use App\Entity\Product;
use App\Repository\ChaptersRepository;
use App\Repository\ProductRepository;
use Framework\Doctrine\EntityManager;
use Framework\Response\Response;
use function App\clearCart;
use function App\getTextLangue;
use function App\isUser;
use function App\startSession;

class ChaptersPage
{

    public function __invoke()
    {
        startSession();
        clearCart();

        $em = EntityManager::getInstance();

        /** @var ChaptersRepository$chaptersRepository */
        $chaptersRepository = $em->getRepository(Chapter::class);
        if (isset ($_GET["id"])) {
            $id = htmlspecialchars($_GET["id"]);
            $chapters = $chaptersRepository->findBy(['product' => $id]);
        }

        $_SESSION['productid'] = $id;



        $args = ['lang' => getTextLangue($_SESSION['locale']), 'chapters' =>$chapters, 'user' => isUser()];
        return new Response('chapterspage.html.twig', $args);
    }

}