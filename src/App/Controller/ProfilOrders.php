<?php
namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\User;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Framework\Doctrine\EntityManager;
use Framework\Response\Response;
use function App\getTextLangue;
use function App\startSession;

class ProfilOrders{
    public function __invoke()
    {
        startSession();
        $lang = getTextLangue($_SESSION["locale"]);

        var_dump($_SESSION["user"]);

        if (isset($_SESSION["user"])){
            $em = EntityManager::getInstance();

            /** @var UserRepository $userRepository */
            $userRepository = $em->getRepository(User::class);
            $user = $userRepository->findOneByEmail($_SESSION["user"]->getEmail());

            /** @var OrderRepository $orderRepository */
            $orderRepository = $em->getRepository(Order::class);


            $orders = $orderRepository->findBy(array('user' => $user), array('orderDateTime' => 'ASC'));

            return new Response('admin/adminOrders.html.twig', ['lang' => $lang, 'orders' => $orders]);
        } else {
            header('Location: /login');
            die;
        }
    }
}

