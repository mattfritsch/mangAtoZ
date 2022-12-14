<?php

namespace App\Controller;


use App\Entity\Chapter;
use App\Repository\ChaptersRepository;
use Framework\Doctrine\EntityManager;
use App\Entity\CartProduct;
use Framework\Response\Response;
use function App\clearCart;
use function App\getTextLangue;
use function App\startSession;

class AddProductToCart{
    public function __invoke()
    {

        startSession();
//        clearCart();

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

        $cartproduct = $_POST;

        function AddProductToCart(array $cartproduct)
        {
            $chapterids = [];
            foreach ($cartproduct as $key => $value) {
                if(is_int($key))
                    array_push($chapterids, $key);
            }

            $em = EntityManager::getInstance();

            $chaptersclass = [];
            foreach ($chapterids as $id) {
                /** @var ChaptersRepository$chaptersRepository */
                $chaptersRepository = $em->getRepository(Chapter::class);
                $chapters = $chaptersRepository->findBy(['product' => $_SESSION['productid']]);

                $chapterid = $chapters[$id-1]->getChapterId();
                array_push($chaptersclass, [$chapterid, 1]);

            }

            $testt = [];
            if(empty($_SESSION['cart'])){
                $_SESSION['cart'] = [];
                foreach($chaptersclass as $chapter){
                    $expire = time() + (60*1);
                    array_push($chapter, $expire);
                    array_push($_SESSION['cart'], $chapter);
                }
                foreach($_SESSION['cart'] as $product){
                    setNewStock($product[0]);
                }
            }
            else {
                $truc=[];
                foreach ($_SESSION['cart'] as $product) {
                    array_push($testt, $product);
                    unset($product[2]);
                    $truc[] = $product;
                }
                foreach($chaptersclass as $chapter){
                    if(!in_array($chapter, $truc)){
                        array_push($chapter, time() + (60*1));
                        array_push($testt, $chapter);
                    }
                }




                $_SESSION['cart'] = $testt;
            }
            foreach($_SESSION['cart'] as $product){
                var_dump($product);
                echo'<br/>';
            }
        }

        AddProductToCart($cartproduct);
        header("Location:/chapterspage?id=".$_SESSION['productid']);
    }
}