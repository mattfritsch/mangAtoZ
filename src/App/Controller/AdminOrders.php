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
use function App\isUser;
use function App\startSession;

class AdminOrders{
    public function __invoke()
    {
        startSession();
        $lang = getTextLangue($_SESSION["locale"]);

        $em = EntityManager::getInstance();

        /** @var UserRepository$userRepository */
        $userRepository = $em->getRepository(User::class);
        $user = $userRepository->findOneByEmail($_SESSION["user"]->getEmail());

        /** @var OrderRepository$orderRepository */
        $orderRepository = $em->getRepository(Order::class);

        if ($user->isAdmin()){
            if ($_SESSION["user"]->getPassword() === $user->getPassword()) {
                if(!$_POST){
                    $orders = $orderRepository->findBy(array(), array('orderDateTime' => 'DESC'));

                    return new Response('admin/adminOrders.html.twig', ['lang' => $lang, 'orders' => $orders, 'user' => isUser()]);
                } else {
                    $order = $orderRepository->findOneBy(array("orderId" => $_POST["orderId"]));
                    $order->setDelivered(!$order->isDelivered());
                    $em->persist($order);
                    $em->flush();

                    $isDelivered = $order->isDelivered();
                    $data = [];
                    if($isDelivered){
                        $data["value"] = $lang["ADMINORDER"]["YES"];
                        $data["btn"] = $lang["ADMINORDER"]["NOTDELIVERED"];
                    } else {
                        $data["value"] = $lang["ADMINORDER"]["NO"];
                        $data["btn"] = $lang["ADMINORDER"]["DELIVERED"];
                    }
                    echo(json_encode($data));
                }
            }
            else {
                echo($lang["ADMINUSERS"]["ERRORPWD"]);
                die;
            }
        }
        else {
            echo($lang["ADMINUSERS"]["ERRORADMIN"]);
            die;
        }
    }
}

