<?php
namespace App\Controller;

use App\Entity\CartProduct;
use App\Entity\Chapter;
use App\Entity\Product;
use App\Entity\User;
use App\Repository\CartProductRepository;
use App\Repository\ChapterRepository;
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

        /** @var ChapterRepository$chapterRepository */
        $chapterRepository = $em->getRepository(Chapter::class);
        $chapter = $chapterRepository->findOneBy(['chapterId' => 1, 'product' => $product]);

        /** @var UserRepository$userRepository */
        $userRepository = $em->getRepository(User::class);
        $user = $userRepository->findOneBy(['uid' => 1]);

        /** @var CartProductRepository$cartProductRepository */
        $cartProductRepository = $em->getRepository(CartProduct::class);
        $cartProduct = $cartProductRepository->findOneBy(['product' => $product, 'user' => $user]);

        echo('<pre>');
        var_dump($product->getCateg()->getCategName());
        echo('</br>');
        var_dump($chapter->getChapterPrice());
        echo('</br>');
        var_dump($cartProduct->getQtt());
        die;
    }
}

