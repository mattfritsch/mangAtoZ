<?php

namespace App\Controller;

use App\Entity\CartProduct;
use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\User;
use App\Repository\CartProductRepository;
use App\Repository\OrderProductRepository;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use Framework\Doctrine\EntityManager;
use Framework\Response\Response;
use function App\getTextLangue;
use function App\isUser;
use function App\startSession;
use function Symfony\Component\String\s;

class PaypalPayement{
    public function __invoke()
    {
        startSession();

        if (isset($_SESSION["user"])){
            $em = EntityManager::getInstance();

            /** @var UserRepository $userRepository */
            $userRepository = $em->getRepository(User::class);
            $user = $userRepository->findOneByEmail($_SESSION["user"]->getEmail());

            /** @var CartProductRepository $cartProductRepository */
            $cartProductRepository = $em->getRepository(CartProduct::class);

            $cart = $cartProductRepository->findBy(array('user' => $user));

            $shippingFees = 0;
            $subtotal = 0;

            foreach ($cart as $product){
                $shippingFees += $product->getQuantite();
                $price = $product->getChapter()->getChapterPrice();
                $qtt = $product->getQuantite();
                $subtotal += $price * $qtt;
            }

            if ($shippingFees !== 0){
                $shippingFees += 1;
            }

            $total = $subtotal + $shippingFees;

            $_SESSION["prixtotal"] = $total;

            $args = ['lang' => getTextLangue($_SESSION['locale']), 'user' => isUser(), 'cart' => $cart,
                'total' => $total, 'shippingFees' => $shippingFees, 'subtotal' => $subtotal];
            return new Response('pagepayement.html.twig', $args );
        } else {
            header('Location: /login');
            die;
        }
    }
}