<?php

namespace App\Controller;

use App\Entity\Chapter;
use App\Entity\User;
use App\Entity\Product;
use App\Repository\CartProductRepository;
use App\Repository\ChaptersRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Framework\Doctrine\EntityManager;
use App\Entity\CartProduct;
use function App\startSession;
use DateTime;

class AddProductToCart{
    public function __invoke()
    {

        startSession();

        function setNewStock($id){
            $em = EntityManager::getInstance();
            /** @var ChaptersRepository$chaptersRepository */
            $chaptersRepository = $em->getRepository(Chapter::class);
            $chapterclass = $chaptersRepository->findOneBy(['chapterId' => $id]);
            $productchapter = $chapterclass->getProduct();
            $stockchapter = $chapterclass->getStock();
            $chapterprice = $chapterclass->getChapterPrice();
            $chapteridvolume = $chapterclass->getChapterName();
            $chapteravailable = $chapterclass->isNotAvailable();

            $chapternewstock = new Chapter();
            $chapternewstock->setChapterId($id);
            $chapternewstock->setStock($stockchapter - 1);
            $chapternewstock->setChapterPrice($chapterprice);
            $chapternewstock->setProduct($productchapter);
            $chapternewstock->setChapterName($chapteridvolume);
            $chapternewstock->setNotAvailable($chapteravailable);

            $em->merge($chapternewstock);
            $em->flush();
        }
        function addProductCart($chapterids){
            $chaptersclass2 = [];

            $em = EntityManager::getInstance();
            /** @var UserRepository$userRepository */
            $userRepository = $em->getRepository(User::class);
            $userclass = $userRepository->findOneBy(['uid' => $_SESSION['user']->getUid()]);

            foreach ($chapterids as $id) {
                /** @var ProductRepository$productRepository */
                $productRepository = $em->getRepository(Product::class);
                $productclass = $productRepository->findOneBy(['productId' => $_SESSION['productid']]);

                /** @var ChaptersRepository $chaptersRepository */
                $chaptersRepository = $em->getRepository(Chapter::class);
                $chapterclass = $chaptersRepository->findOneBy(['chapterName' => $id , 'product' => $productclass]);

                array_push($chaptersclass2, $chapterclass);
            }

            /** @var CartProductRepository$cartproductRepository */
            $cartproductRepository = $em->getRepository(CartProduct::class);
            $cartproductclass = $cartproductRepository->findBy(['user' => $userclass]);

            $bidule= [];
            foreach($cartproductclass as $cartproduct){
                array_push($bidule, $cartproduct->getChapter());
            }

            foreach($chaptersclass2 as $chapter){
                if(!in_array($chapter, $bidule)) {;
                $cartproduct = new CartProduct();
                $cartproduct->setUser($userclass);
                $cartproduct->setChapter($chapter);
                $cartproduct->setQuantite(1);
                $cartproduct->setCartTime(new DateTime());

                $em->persist($cartproduct);
                $em->flush();
                }

            }
        }
        function setup($chapterids){
            $chaptersclass = [];
            $em = EntityManager::getInstance();
            foreach ($chapterids as $id) {
                /** @var ChaptersRepository $chaptersRepository */
                $chaptersRepository = $em->getRepository(Chapter::class);
                $chapters = $chaptersRepository->findBy(['product' => $_SESSION['productid']]);

                $chapterid = $chapters[$id - 1]->getChapterId();
                var_dump($chapterid);
                array_push($chaptersclass, [$chapterid, 1]);

            }

            $cart = [];
            if (empty($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
                foreach ($chaptersclass as $chapter) {
                    array_push($_SESSION['cart'], $chapter);
                }
                foreach ($_SESSION['cart'] as $product) {
                    setNewStock($product[0]);
                    if(isset($_SESSION['user'])){
                        addProductCart($chapterids);
                    }
                }
            } else {
                foreach ($_SESSION['cart'] as $product) {
                    array_push($cart, $product);
                }
                var_dump($cart);
                foreach ($chaptersclass as $chapter) {
                    var_dump($chapter);
                    if (!in_array($chapter, $cart)) {
                        array_push($cart, $chapter);
                        if(isset($_SESSION['user'])) {
                            addProductCart($chapterids);
                        }
                    }
                }
                $_SESSION['cart'] = $cart;
            }
        }

        $cartproduct = $_POST;

        function AddProductToCart(array $cartproduct)
        {
            $chapterids = [];
            foreach ($cartproduct["chapters"] as $value) {
                array_push($chapterids, $value);
            }
                setup($chapterids);
        }

        AddProductToCart($cartproduct);
        header("Location:/chapterspage?id=".$_SESSION['productid']);
    }
}