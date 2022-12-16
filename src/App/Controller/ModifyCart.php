<?php

namespace App\Controller;

use App\Entity\Chapter;
use App\Entity\User;
use App\Repository\CartProductRepository;
use App\Repository\ChaptersRepository;
use App\Repository\UserRepository;
use Framework\Doctrine\EntityManager;
use App\Entity\CartProduct;
use function App\startSession;
use DateTime;

class ModifyCart
{
    public function __invoke()
    {

        startSession();
//        clearCart();
        var_dump($_POST);
        $em = EntityManager::getInstance();
        if (isset($_POST['modify'])) {
            for ($i = 0; $i < count($_SESSION['cart']); $i++) {
                if ($_POST[$_SESSION['cart'][$i][0]] > $_SESSION['cart'][$i][1]) {
                    /** @var ChaptersRepository $chaptersRepository */
                    $chaptersRepository = $em->getRepository(Chapter::class);
                    $chapterclass = $chaptersRepository->findOneBy(['chapterId' => $_SESSION['cart'][$i][0]]);
                    $chapterstock = $chapterclass->getStock() - $_POST[$_SESSION['cart'][$i][0]] + $_SESSION['cart'][$i][1];
                    $chapterprice = $chapterclass->getChapterPrice();
                    $chapterproduct = $chapterclass->getProduct();
                    $chapterName = $chapterclass->getChapterName();
                    $chapteravailable = $chapterclass->isNotAvailable();


                    if(!isset($_SESSION['user'])) {
                        $_SESSION['cart'][$i] = [$_SESSION['cart'][$i][0], $_POST[$_SESSION['cart'][$i][0]]];
                    }
                    $chapternewstock = new Chapter();

                    $chapternewstock->setChapterId($_SESSION['cart'][$i][0]);
                    $chapternewstock->setStock($chapterstock);
                    $chapternewstock->setChapterPrice($chapterprice);
                    $chapternewstock->setProduct($chapterproduct);
                    $chapternewstock->setChapterName($chapterName);
                    $chapternewstock->setNotAvailable($chapteravailable);

                    $em->merge($chapternewstock);
                    $em->flush();
                }
                if ($_POST[$_SESSION['cart'][$i][0]] < $_SESSION['cart'][$i][1]) {
                    /** @var ChaptersRepository $chaptersRepository */
                    $chaptersRepository = $em->getRepository(Chapter::class);
                    $chapterclass = $chaptersRepository->findOneBy(['chapterId' => $_SESSION['cart'][$i][0]]);
                    $chapterstock = $chapterclass->getStock() - $_POST[$_SESSION['cart'][$i][0]] + $_SESSION['cart'][$i][1];
                    $chapterprice = $chapterclass->getChapterPrice();
                    $chapterproduct = $chapterclass->getProduct();
                    $chapterName = $chapterclass->getChapterName();
                    $chapteravailable = $chapterclass->isNotAvailable();

                    if(!isset($_SESSION['user'])) {
                        $_SESSION['cart'][$i] = [$_SESSION['cart'][$i][0], $_POST[$_SESSION['cart'][$i][0]]];
                    }
                    $chapternewstock = new Chapter();

                    $chapternewstock->setChapterId($_SESSION['cart'][$i][0]);
                    $chapternewstock->setStock($chapterstock);
                    $chapternewstock->setChapterPrice($chapterprice);
                    $chapternewstock->setProduct($chapterproduct);
                    $chapternewstock->setChapterName($chapterName);
                    $chapternewstock->setNotAvailable($chapteravailable);

                    $em->merge($chapternewstock);
                    $em->flush();
                }

                if(isset($_SESSION['user'])) {
                    if ($_POST[$_SESSION['cart'][$i][0]] > $_SESSION['cart'][$i][1]) {
                        echo'plus';
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

                        $cartproductnewquantite->setQuantite($_POST[$_SESSION['cart'][$i][0]]);
                        $cartproductnewquantite->setChapter($chapterclass);
                        $cartproductnewquantite->setUser($userclass);
                        $cartproductnewquantite->setCartTime($cartproductclass->getCartTime());

                        $em->merge($cartproductnewquantite);
                        $em->flush();
                        $_SESSION['cart'][$i] = [$_SESSION['cart'][$i][0], $_POST[$_SESSION['cart'][$i][0]]];

                    }
                    if ($_POST[$_SESSION['cart'][$i][0]] < $_SESSION['cart'][$i][1]) {
                        echo'moins';
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

                        if($_POST[$_SESSION['cart'][$i][0]]==0){
                            $cartproductnewquantite->setQuantite($cartproductclass->getQuantite());
                            $cartproductnewquantite->setChapter($chapterclass);
                            $cartproductnewquantite->setUser($userclass);
                            $cartproductnewquantite->setCartTime($cartproductclass->getCartTime());

                            $entity = $em->merge($cartproductnewquantite);
                            $em->remove($entity);
                            $em->flush();
                        }
                        else {

                            $cartproductnewquantite->setQuantite($_POST[$_SESSION['cart'][$i][0]]);
                            $cartproductnewquantite->setChapter($chapterclass);
                            $cartproductnewquantite->setUser($userclass);
                            $cartproductnewquantite->setCartTime($cartproductclass->getCartTime());

                            $em->merge($cartproductnewquantite);
                            $em->flush();
                        }
                        $_SESSION['cart'][$i] = [$_SESSION['cart'][$i][0], $_POST[$_SESSION['cart'][$i][0]]];
                    }
                }
            }

            for ($i = 0; $i < count($_SESSION['cart']); $i++) {
                if($_POST[$_SESSION['cart'][$i][0]] == 0){
                    array_splice($_SESSION['cart'], $i,$i);
                }

            }
        }
    }
}