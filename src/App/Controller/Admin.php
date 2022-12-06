<?php
namespace App\Controller;

use App\Entity\CartProduct;
use App\Entity\Chapter;
use App\Entity\Product;
use App\Entity\ProductCateg;
use App\Entity\User;
use App\Repository\CartProductRepository;
use App\Repository\ChaptersRepository;
use App\Repository\ProductCategRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Framework\Doctrine\EntityManager;
use Framework\Response\Response;
use function App\getTextLangue;

class Admin{
    public function __invoke()
    {

//        var_dump($_SESSION["test"]);
//        die;

//        $em = EntityManager::getInstance();
//
//        /** @var ProductRepository$productRepository */
//        $productRepository = $em->getRepository(Product::class);
//        $products = $productRepository->findBy(array(), array('productName' => 'ASC'));;

//        return new Response('admin.html.twig', ['lang' => getTextLangue('trad'), 'products' => $products]);



//        ----------------------------EXEMPLES D'UTILISATION DES CLES ETRANGERES------------------------------------

        $em = EntityManager::getInstance();

        /** @var ProductRepository$productRepository */
        $productRepository = $em->getRepository(Product::class);
        $product = $productRepository->findOneBy(['productId' => 1]);

        /** @var ProductCategRepository$productCategRepository */
        $productCategRepository = $em->getRepository(ProductCateg::class);
        $productCategs = $productCategRepository->findBy(['product' => $product]);

        echo('<pre>');
        echo($product->getProductName());
        echo '<br>';
        foreach ( $productCategs as $categ ){
            echo($categ->getCateg()->getCategName());
            echo '<br>';
        }
        die;
    }
}

