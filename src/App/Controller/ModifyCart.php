<?php

namespace App\Controller;


use App\Entity\Chapter;
use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\User;
use App\Repository\CartProductRepository;
use App\Repository\ChaptersRepository;
use App\Repository\UserRepository;
use Framework\Doctrine\EntityManager;
use App\Entity\CartProduct;
use Framework\Response\Response;
use function App\clearCart;
use function App\getTextLangue;
use function App\startSession;
use DateTime;

class ModifyCart{
    public function __invoke()
    {

        startSession();
//        clearCart();
        var_dump($_POST);
        $em = EntityManager::getInstance();
        if(isset($_POST['modify'])){
            // ------------------------------------------------------
            // session remove stock
            //-------------------------------------------------------

            for($i = 0; $i<count($_SESSION['cart']); $i++) {
                if ($_POST[$_SESSION['cart'][$i][0]] > $_SESSION['cart'][$i][1]) {
                    /** @var ChaptersRepository $chaptersRepository */
                    $chaptersRepository = $em->getRepository(Chapter::class);
                    $chapterclass = $chaptersRepository->findOneBy(['chapterId' => $_SESSION['cart'][$i][0]]);
                    $chapterstock = $chapterclass->getStock() - ($_POST[$_SESSION['cart'][$i][0]] - $_SESSION['cart'][$i][1]);
                    $chapterprice = $chapterclass->getChapterPrice();
                    $chapterproduct = $chapterclass->getProduct();
                    $chapterName = $chapterclass->getChapterName();
                    $chapteravailable = $chapterclass->isNotAvailable();

                    $chapternewstock = new Chapter();

                    $chapternewstock->setChapterId($_SESSION['cart'][$i][0]);
                    $chapternewstock->setStock($chapterstock);
                    $chapternewstock->setChapterPrice($chapterprice);
                    $chapternewstock->setProduct($chapterproduct);
                    $chapternewstock->setChapterName($chapterName);
                    $chapternewstock->setNotAvailable($chapteravailable);

                    $em->merge($chapternewstock);
                    $em->flush();

                    if (isset($_SESSION['user'])) {
                        /** @var UserRepository $userRepository */
                        $userRepository = $em->getRepository(User::class);
                        $userclass = $userRepository->findOneBy(['uid' => $_SESSION['user']->getUid()]);


                        /** @var ChaptersRepository $chaptersRepository */
                        $chaptersRepository = $em->getRepository(Chapter::class);
                        $chapterclass = $chaptersRepository->findOneBy(['chapterId' => $_SESSION['cart'][$i][0]]);

                        /** @var CartProductRepository $cartproductRepository */
                        $cartproductRepository = $em->getRepository(CartProduct::class);
                        $cartproductclass = $cartproductRepository->findOneBy(['user' => $userclass, 'chapter' => $chapterclass]);

                        $cartproductnewquantite = new CartProduct();

                        var_dump($cartproductclass->getQuantite());
                        var_dump($_SESSION['cart'][$i][1]);
                        var_dump($_POST[$_SESSION['cart'][$i][0]]);
                        $cartproductnewquantite->setQuantite($_POST[$_SESSION['cart'][$i][0]]);
                        $cartproductnewquantite->setChapter($chapterclass);
                        $cartproductnewquantite->setUser($userclass);


                        $em->merge($cartproductnewquantite);
                        $em->flush();
                    }

                }
            }


                //-----------------------------------
                // database remove stock
                //---------------------------------
                /** @var UserRepository $userRepository */
                $userRepository = $em->getRepository(User::class);
                $userclass = $userRepository->findOneBy(['uid' => $_SESSION['user']->getUid()]);


                /** @var CartProductRepository $cartProductRepository */
                $cartProductRepository = $em->getRepository(CartProduct::class);
                $cartproductclass = $cartProductRepository->findBy(['user' => $userclass]);

                echo'coucou';
//                var_dump($cartproductclass);

                for($i = 0; $i<count($cartproductclass); $i++) {
                    echo'coucou';
                    var_dump($cartproductclass[$i]->getQuantite());
                    if ($_POST[$cartproductclass[$i]->getChapter()->getChapterId()] > $cartproductclass[$i]->getQuantite()) {
                        echo'coucou';
                        /** @var ChaptersRepository $chaptersRepository */
                        $chaptersRepository = $em->getRepository(Chapter::class);
                        $chapterclass = $chaptersRepository->findOneBy(['chapterId' => $cartproductclass[$i]->getChapter()->getChapterId()]);
//                        var_dump($chapterclass);
//                        die;
                        $chapterid = $chapterclass->getChapterId();
                        $chapterstock = $chapterclass->getStock() + $_POST[$chapterid] - $cartproductclass[$i]->getQuantite();
                        $chapterprice = $chapterclass->getChapterPrice();
                        $chapterproduct = $chapterclass->getProduct();
                        $chapterName = $chapterclass->getChapterName();
                        $chapteravailable = $chapterclass->isNotAvailable();

                        $chapternewstock = new Chapter();

                        $chapternewstock->setChapterId($chapterid);
                        $chapternewstock->setStock($chapterstock);
                        $chapternewstock->setChapterPrice($chapterprice);
                        $chapternewstock->setProduct($chapterproduct);
                        $chapternewstock->setChapterName($chapterName);
                        $chapternewstock->setNotAvailable($chapteravailable);

                        $em->merge($chapternewstock);
                        $em->flush();

                        if (isset($_SESSION['user'])) {

                            /** @var CartProductRepository $cartproductRepository */
                            $cartproductRepository = $em->getRepository(CartProduct::class);
                            $cartproductclass2 = $cartproductRepository->findOneBy(['user' => $userclass, 'chapter' => $chapterclass]);

                            $cartproductnewquantite = new CartProduct();

                            var_dump($cartproductclass2->getQuantite());
                            var_dump($_SESSION['cart'][$i][1]);
                            var_dump($_POST[$_SESSION['cart'][$i][0]]);
                            $cartproductnewquantite->setQuantite($_POST[$chapterid]);
                            $cartproductnewquantite->setChapter($chapterclass);
                            $cartproductnewquantite->setUser($userclass);


                            $em->merge($cartproductnewquantite);
                            $em->flush();
                        }

                    }
                }





                // ------------------------------------------------------
                // session add stock
                //-------------------------------------------------------
                if($_POST[$_SESSION['cart'][$i][0]] < $_SESSION['cart'][$i][1]){

                    /** @var ChaptersRepository$chaptersRepository */
                    $chaptersRepository = $em->getRepository(Chapter::class);
                    $chapterclass = $chaptersRepository->findOneBy(['chapterId' => $_SESSION['cart'][$i][0]]);
                    $chapterstock = $chapterclass->getStock() + ( $_SESSION['cart'][$i][1] - $_POST[$_SESSION['cart'][$i][0]]);
                    $chapterprice = $chapterclass->getChapterPrice();
                    $chapterproduct = $chapterclass->getProduct();
                    $chapterName = $chapterclass->getChapterName();
                    $chapteravailable = $chapterclass->isNotAvailable();


                    $chapternewstock = new Chapter();

                    $chapternewstock->setChapterId($_SESSION['cart'][$i][0]);
                    $chapternewstock->setStock($chapterstock);
                    $chapternewstock->setChapterPrice($chapterprice);
                    $chapternewstock->setProduct($chapterproduct);
                    $chapternewstock->setChapterName($chapterName);
                    $chapternewstock->setNotAvailable($chapteravailable);

                    $em->merge($chapternewstock);
                    $em->flush();

                    if(isset($_SESSION['user'])){
                        /** @var UserRepository$userRepository */
                        $userRepository = $em->getRepository(User::class);
                        $userclass = $userRepository->findOneBy(['uid' => $_SESSION['user']->getUid()]);

                        /** @var ChaptersRepository$chaptersRepository */
                        $chaptersRepository = $em->getRepository(Chapter::class);
                        $chapterclass = $chaptersRepository->findOneBy(['chapterId' => $_SESSION['cart'][$i][0]]);

                        /** @var CartProductRepository$cartproductRepository */
                        $cartproductRepository = $em->getRepository(CartProduct::class);
                        $cartproductclass = $cartproductRepository->findOneBy(['user' => $userclass, 'chapter'=>$chapterclass]);

                        $cartproductnewquantite = new CartProduct();
                        var_dump($cartproductclass->getQuantite());
                        var_dump($_SESSION['cart'][$i][1]);
                        var_dump($_POST[$_SESSION['cart'][$i][0]]);
                        if($_POST[$_SESSION['cart'][$i][0]] == null){
                            $cartproductnewquantite->setQuantite($cartproductclass->getQuantite());
                            $cartproductnewquantite->setChapter($chapterclass);
                            $cartproductnewquantite->setUser($userclass);
                            $entity = $em->merge($cartproductnewquantite);
                            $em->remove($entity);
                            $em->flush();
                        }else{
                            $cartproductnewquantite->setQuantite($_POST[$_SESSION['cart'][$i][0]]);
                            $cartproductnewquantite->setChapter($chapterclass);
                            $cartproductnewquantite->setUser($userclass);


                            $em->merge($cartproductnewquantite);
                            $em->flush();
                        }


                    }


                $_SESSION['cart'][$i][1] = (int)$_POST[ $_SESSION['cart'][$i][0]];
            }

            //-----------------------------------
            // database add stock
            //---------------------------------
            /** @var UserRepository $userRepository */
            $userRepository = $em->getRepository(User::class);
            $userclass = $userRepository->findOneBy(['uid' => $_SESSION['user']->getUid()]);


            /** @var CartProductRepository $cartProductRepository */
            $cartProductRepository = $em->getRepository(CartProduct::class);
            $cartproductclass = $cartProductRepository->findBy(['user' => $userclass]);

//            echo'coucou';
//                var_dump($cartproductclass);

            for($i = 0; $i<count($cartproductclass); $i++) {
//                echo'coucou';
                var_dump($cartproductclass[$i]->getQuantite());
                if ($_POST[$cartproductclass[$i]->getChapter()->getChapterId()] < $cartproductclass[$i]->getQuantite()) {
                    echo'coucou';
                    /** @var ChaptersRepository $chaptersRepository */
                    $chaptersRepository = $em->getRepository(Chapter::class);
                    $chapterclass = $chaptersRepository->findOneBy(['chapterId' => $cartproductclass[$i]->getChapter()->getChapterId()]);
//                        var_dump($chapterclass);
//                        die;
                    $chapterid = $chapterclass->getChapterId();
                    $chapterstock = $chapterclass->getStock() - $_POST[$chapterid] + $cartproductclass[$i]->getQuantite();
                    $chapterprice = $chapterclass->getChapterPrice();
                    $chapterproduct = $chapterclass->getProduct();
                    $chapterName = $chapterclass->getChapterName();
                    $chapteravailable = $chapterclass->isNotAvailable();

                    $chapternewstock = new Chapter();

                    $chapternewstock->setChapterId($chapterid);
                    $chapternewstock->setStock($chapterstock);
                    $chapternewstock->setChapterPrice($chapterprice);
                    $chapternewstock->setProduct($chapterproduct);
                    $chapternewstock->setChapterName($chapterName);
                    $chapternewstock->setNotAvailable($chapteravailable);

                    $em->merge($chapternewstock);
                    $em->flush();

                    if (isset($_SESSION['user'])) {
//                        echo'coucou';
                        /** @var CartProductRepository $cartproductRepository */
                        $cartproductRepository = $em->getRepository(CartProduct::class);
                        $cartproductclass2 = $cartproductRepository->findOneBy(['user' => $userclass, 'chapter' => $chapterclass]);

                        $cartproductnewquantite = new CartProduct();

                        var_dump($cartproductclass2->getQuantite());
                        var_dump($_SESSION['cart'][$i][1]);
                        var_dump($_POST[$_SESSION['cart'][$i][0]]);
                        $cartproductnewquantite->setQuantite($_POST[$chapterid]);
                        $cartproductnewquantite->setChapter($chapterclass);
                        $cartproductnewquantite->setUser($userclass);


                        $em->merge($cartproductnewquantite);
                        $em->flush();
                    }

                }
            }

            $test = [];
            for($i = 0; $i<count($_SESSION['cart']); $i++){
                if($_SESSION['cart'][$i][1] !== 0){
                    array_push($test, $_SESSION['cart'][$i]);
                }
            }
            $_SESSION['cart'] = $test;


//            header("Location:/panier");

        }elseif(isset($_POST['validate'])){
            if(!isset($_SESSION['user'])){
                ?>
                <script type="text/javascript">
                window.location.href = "/login";
                    </script>
                <?php
            }
            else{
                $nombrechap = [];
                $prixchap = [];
                for($i = 0; $i<count($_SESSION['cart']); $i++) {
                    array_push($nombrechap, $_SESSION['cart'][$i][1]);

                    /** @var ChaptersRepository$chaptersRepository */
                    $chaptersRepository = $em->getRepository(Chapter::class);
                    $chapterclass = $chaptersRepository->findOneBy(['chapterId' => $_SESSION['cart'][$i][0]]);
                    array_push($prixchap, $chapterclass->getChapterPrice());
                }

                $prixtotal = 0;
                for($i=0;$i<count($prixchap);$i++){
                    $prixtotal = $prixtotal + $nombrechap[$i] * $prixchap[$i];
                }

                $totalchap = 0;
                for($i=0;$i<count($nombrechap);$i++){
                    $totalchap = $totalchap +  $nombrechap[$i];
                }

                for($i=0; $i<$totalchap; $i++){
                    if($i==0){
                        $prixtotal = $prixtotal + 2;
                    }
                    else{
                        $prixtotal = $prixtotal + 1;
                    }
                }
                $_SESSION['prixtotal'] = $prixtotal;
                var_dump($prixtotal);



            }

            header("Location:/payement");


        }
    }
}