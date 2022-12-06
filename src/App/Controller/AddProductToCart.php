<?php

namespace App\Controller;


use Framework\Doctrine\EntityManager;
use App\Entity\CartProduct;

class AddProductToCart{
    public function __invoke()
    {

        $cartproduct = $_POST;

        function AddProductToCart(array $cartproduct)
        {
            $chapterids = [];
            foreach ($cartproduct as $key => $value) {
                    array_push($chapterids, $key);
            }
            var_dump($chapterids);
            die;
            $em = EntityManager::getInstance();

            foreach ($chapterids as $id) {
                $cart_product = new CartProduct();
                $cart_product->setProductId((int)$cartproduct["productId"]);
                $cart_product->setChapterId($id);
                $cart_product->setCartId(1);
                $cart_product->setQtt(1);

                $em->persist($cart_product);
                $em->flush();
            }
        }
        AddProductToCart($cartproduct);


    }
}