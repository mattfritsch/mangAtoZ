<?php

namespace App\Controller;


use App\Entity\Chapter;
use App\Repository\ChaptersRepository;
use Framework\Doctrine\EntityManager;
use App\Entity\CartProduct;
use Framework\Response\Response;
use function App\getTextLangue;
use function App\startSession;

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

            $chapternewstock = new Chapter();
            $chapternewstock->setChapterId($id);
            $chapternewstock->setStock($stockchapter - 1);
            $chapternewstock->setChapterPrice($chapterprice);
            $chapternewstock->setProduct($productchapter);
            $chapternewstock->setChapterName($chapteridvolume);
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
                $chapters = $chaptersRepository->findBy(['product' => '4']);

                $chapterid = $chapters[$id-1]->getChapterId();
                array_push($chaptersclass, [$chapterid, 1]);

            }

            $testt = [];
            if(empty($_SESSION['cart'])){
                $_SESSION['cart'] = $chaptersclass;
                foreach($_SESSION['cart'] as $product){
                    setNewStock($product[0]);
                }
            }
            else {
                foreach ($_SESSION['cart'] as $product) {

                    array_push($testt, $product);

                }
                foreach($chaptersclass as $class){
                    if(!in_array($class, $testt)){
                        array_push($testt, $class);
                        setNewStock($class[0]);
                    }
                }
                $_SESSION['cart'] = $testt;
            }
        }

        AddProductToCart($cartproduct);
        header("Location:/chapterspage");
    }
}