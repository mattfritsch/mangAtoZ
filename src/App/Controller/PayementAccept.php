<?php
namespace App\Controller;

use App\Entity\CartProduct;
use App\Entity\Chapter;
use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\User;
use App\Repository\CartProductRepository;
use App\Repository\ChaptersRepository;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use DateTime;
use Framework\Doctrine\EntityManager;
use Framework\Response\Response;
use function App\getTextLangue;
use function App\isUser;
use function App\startSession;

class PayementAccept{
    public function __invoke()
    {
        startSession();
        $em = EntityManager::getInstance();

        /** @var UserRepository$userRepository */
        $userRepository = $em->getRepository(User::class);
        $userclass = $userRepository->findOneBy(['uid' => $_SESSION['user']->getUid()]);

        /** @var CartProductRepository $cartProductRepository */
        $cartProductRepository = $em->getRepository(CartProduct::class);
        $cart = $cartProductRepository->findBy(array('user' => $userclass));

        if (sizeof($cart) !== 0){
            foreach ($cart as $product) {
                $em->remove($product);
            }
            $em->flush();

            $date = new DateTime();

            $orderProducts = [];

            for ($i = 0; $i < count($_SESSION['cart']); $i++) {
                /** @var ChaptersRepository $chaptersRepository */
                $chaptersRepository = $em->getRepository(Chapter::class);
                $chapterclass = $chaptersRepository->findOneBy(['chapterId' => $_SESSION['cart'][$i][0]]);


                $orderproduct = new OrderProduct();
                $orderproduct->setChapter($chapterclass);
                $orderproduct->setQtt($_SESSION['cart'][$i][1]);
                $orderProducts[] = $orderproduct;
                $em->persist($orderproduct);
                $em->flush();
            }
            $_SESSION['cart'] = [];


            $order = new Order();
            $order->setUser($userclass);
            $order->setOrderId(0);
            $order->setOrderDateTime($date);
            $order->setTotalPrice($_SESSION['prixtotal']);
            $order->setDelivered(0);
            $order->setOrderProducts($orderProducts);
            $em->persist($order);
            $em->flush();
        }



        $args = ['lang' => getTextLangue($_SESSION['locale']), 'user' => isUser()];
        return new Response('pagepayementaccept.html.twig', $args );

    }
}