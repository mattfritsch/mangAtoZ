<?php
namespace App\Controller;

use App\Entity\Chapter;
use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\User;
use App\Repository\ChaptersRepository;
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

        $date = new DateTime();
        $order = new Order();
        $order->setUser($userclass);
        $order->setOrderId(0);
        $order->setOrderDateTime($date);
        $order->setTotalPrice($_SESSION['prixtotal']);
        $order->setDelivered(0);
        $em->persist($order);
        $em->flush();

        for($i = 0; $i<count($_SESSION['cart']); $i++) {
            /** @var ChaptersRepository$chaptersRepository */
            $chaptersRepository = $em->getRepository(Chapter::class);
            $chapterclass = $chaptersRepository->findOneBy(['chapterId' => $_SESSION['cart'][$i][0]]);

            /** @var OrderRepository$orderRepository */
            $orderRepository = $em->getRepository(Order::class);
            $orderclass = $orderRepository->findOneBy(['orderDateTime' => $date ,'totalPrice' => $_SESSION['prixtotal']]);

            $orderproduct = new OrderProduct();
            $orderproduct->setChapter($chapterclass);
            $orderproduct->setOrder($orderclass);
            $orderproduct->setQtt($_SESSION['cart'][$i][1]);
            $em->persist($orderproduct);
            $em->flush();
        }
        $_SESSION['cart'] = [];

        $args = ['lang' => getTextLangue($_SESSION['locale'])];
        return new Response('pagepayementaccept.html.twig', $args );

    }
}