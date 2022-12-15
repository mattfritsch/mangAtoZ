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
use Framework\Response\Response;
use function App\clearCart;
use function App\getTextLangue;
use function App\startSession;
use DateTime;

class AddProductToCart{
    public function __invoke()
    {

        startSession();
//        clearCart();
//        var_dump($_POST);
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
//            var_dump($_SESSION);
            $userclass = $userRepository->findOneBy(['uid' => $_SESSION['user']->getUid()]);
            foreach ($chapterids as $id) {
                /** @var ProductRepository$productRepository */
                $productRepository = $em->getRepository(Product::class);
                $productclass = $productRepository->findOneBy(['productId' => $_SESSION['productid']]);

                /** @var ChaptersRepository $chaptersRepository */
                $chaptersRepository = $em->getRepository(Chapter::class);
                $chapterclass = $chaptersRepository->findOneBy(['chapterName' => $id , 'product' => $productclass]);
//                    var_dump($chapterclass);
                array_push($chaptersclass2, $chapterclass);
            }
//                var_dump($chaptersclass2);
            /** @var CartProductRepository$cartproductRepository */
            $cartproductRepository = $em->getRepository(CartProduct::class);
            $cartproductclass = $cartproductRepository->findBy(['user' => $userclass]);
//            var_dump($cartproductclass);
            $bidule= [];
            foreach($cartproductclass as $cartproduct){
                array_push($bidule, $cartproduct->getChapter());
            }
//            var_dump($bidule);

            foreach($chaptersclass2 as $chapter){
                if(!in_array($chapter, $bidule)) {
//                var_dump($chapter);
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
        function truc($chapterids){
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

            $testt = [];
            if (empty($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
                foreach ($chaptersclass as $chapter) {
                    array_push($_SESSION['cart'], $chapter);
                }
                foreach ($_SESSION['cart'] as $product) {
                    setNewStock($product[0]);
                    if(isset($_SESSION['user'])){
                        addProductCart($chapterids);
                echo'coucou';
                    }

                }
            } else {
//                var_dump($_SESSION['cart']);

                $truc = [];
                foreach ($_SESSION['cart'] as $product) {
                    array_push($testt, $product);
//                    $truc[] = $product;
                }
                var_dump($testt);
                foreach ($chaptersclass as $chapter) {
                    var_dump($chapter);
                    if (!in_array($chapter, $testt)) {
                        array_push($testt, $chapter);
                        if(isset($_SESSION['user'])) {
                            addProductCart($chapterids);
                        }
                        echo'coucou';

                    }
                }


                $_SESSION['cart'] = $testt;
            }
            foreach ($_SESSION['cart'] as $product) {
//                var_dump($product);
                echo '<br/>';
            }
        }

        $cartproduct = $_POST;

        function AddProductToCart(array $cartproduct)
        {
            $chapterids = [];
            foreach ($cartproduct["chapters"] as $value) {
                array_push($chapterids, $value);
            }
            var_dump($chapterids);
                truc($chapterids);
        }

        AddProductToCart($cartproduct);
//        header("Location:/chapterspage?id=".$_SESSION['productid']);
    }
}