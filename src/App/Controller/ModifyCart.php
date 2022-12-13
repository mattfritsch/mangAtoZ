<?php

namespace App\Controller;


use App\Entity\Chapter;
use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\User;
use App\Repository\ChaptersRepository;
use App\Repository\UserRepository;
use Framework\Doctrine\EntityManager;
use App\Entity\CartProduct;
use Framework\Response\Response;
use function App\getTextLangue;
use function App\startSession;
use DateTime;

class ModifyCart{
    public function __invoke()
    {

        startSession();

        $em = EntityManager::getInstance();
        if(isset($_POST['modify'])){
            for($i = 0; $i<count($_SESSION['cart']); $i++){
                if($_POST[$_SESSION['cart'][$i][0]] > $_SESSION['cart'][$i][1]){
                    /** @var ChaptersRepository$chaptersRepository */
                    $chaptersRepository = $em->getRepository(Chapter::class);
                    $chapterclass = $chaptersRepository->findOneBy(['chapterId' => $_SESSION['cart'][$i][0]]);
                    $chapterstock = $chapterclass->getStock() - ($_POST[$_SESSION['cart'][$i][0]] - $_SESSION['cart'][$i][1]);
                    $chapterprice = $chapterclass->getChapterPrice();
                    $chapterproduct = $chapterclass->getProduct();
                    $chapterName = $chapterclass->getChapterName();

                    $chapternewstock = new Chapter();

                    $chapternewstock->setChapterId($_SESSION['cart'][$i][0]);
                    $chapternewstock->setStock($chapterstock);
                    $chapternewstock->setChapterPrice($chapterprice);
                    $chapternewstock->setProduct($chapterproduct);
                    $chapternewstock->setChapterName($chapterName);

                    $em->merge($chapternewstock);
                    $em->flush();
                }

                if($_POST[$_SESSION['cart'][$i][0]] < $_SESSION['cart'][$i][1]){
                    /** @var ChaptersRepository$chaptersRepository */
                    $chaptersRepository = $em->getRepository(Chapter::class);
                    $chapterclass = $chaptersRepository->findOneBy(['chapterId' => $_SESSION['cart'][$i][0]]);
                    $chapterstock = $chapterclass->getStock() + ( $_SESSION['cart'][$i][1] - $_POST[$_SESSION['cart'][$i][0]]);
                    $chapterprice = $chapterclass->getChapterPrice();
                    $chapterproduct = $chapterclass->getProduct();
                    $chapterName = $chapterclass->getChapterName();

                    $chapternewstock = new Chapter();

                    $chapternewstock->setChapterId($_SESSION['cart'][$i][0]);
                    $chapternewstock->setStock($chapterstock);
                    $chapternewstock->setChapterPrice($chapterprice);
                    $chapternewstock->setProduct($chapterproduct);
                    $chapternewstock->setChapterName($chapterName);
                    $em->merge($chapternewstock);
                    $em->flush();
                }
                $_SESSION['cart'][$i][1] = (int)$_POST[ $_SESSION['cart'][$i][0]];
            }
            header("Location:/panier");

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

                /** @var UserRepository$userRepository */
                $userRepository = $em->getRepository(User::class);
                $userclass = $userRepository->findOneBy(['uid' => $_SESSION['user']->getUid()]);

                $date = new DateTime();
                $order = new Order();
                $order->setUser($userclass);
                $order->setOrderId(0);
                $order->setOrderDateTime($date);
                $order->setTotalPrice($prixtotal);
                $order->setDelivered(0);
                $em->persist($order);
                $em->flush();

                for($i = 0; $i<count($_SESSION['cart']); $i++) {
                    /** @var ChaptersRepository$chaptersRepository */
                    $chaptersRepository = $em->getRepository(Chapter::class);
                    $chapterclass = $chaptersRepository->findOneBy(['chapterId' => $_SESSION['cart'][$i][0]]);

                    /** @var OrderRepository$orderRepository */
                    $orderRepository = $em->getRepository(Order::class);
                    $orderclass = $orderRepository->findOneBy(['orderDateTime' => $date ,'totalPrice' => $prixtotal]);

                    $orderproduct = new OrderProduct();
                    $orderproduct->setChapter($chapterclass);
                    $orderproduct->setOrder($orderclass);
                    $orderproduct->setQtt($_SESSION['cart'][$i][1]);
                $em->persist($orderproduct);
                $em->flush();
                }
            }
        }
    }
}