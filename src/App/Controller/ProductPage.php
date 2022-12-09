<?php

namespace App\Controller;


use App\Repository\CategRepository;
use App\Repository\ProductRepository;
use Framework\Doctrine\EntityManager;
use Framework\Response\Response;
use App\Entity\Product;
use App\Entity\Categ;
use function App\getTextLangue;

class ProductPage{
    public function __invoke()
    {
        $search = null;

        if (isset($_POST['submitButton'])) { //check if form was submitted
            if ($_POST['searchBar'] == null) {
                $search = null;
            } else {
                $input = $_POST['searchBar']; //get input text
                $search = $input;
            }
        }

        $em = EntityManager::getInstance();
        $qb = $em->createQueryBuilder();

        /** @var ProductRepository $productRepository */
        $productRepository = $em->getRepository(Product::class);

        if ($search === null){
            $products = $productRepository->findAll();
        }
        else {
            $products = $productRepository->findBy(['productName' => $search]);
        }

        /** @var CategRepository$categRepository */
        $categRepository = $em->getRepository(Categ::class);
        $categs = $categRepository->findAll();

        $args = ['lang' => getTextLangue('en'), 'products' => $products, 'categs' => $categs,
            'search' => $search];
        return new Response('productPage.html.twig', $args);
    }
}
