<?php

namespace App\Controller;


use App\Entity\Chapter;
use App\Entity\User;
use App\Repository\ChaptersRepository;
use App\Repository\UserRepository;
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
                if(is_int($key))
                    array_push($chapterids, $key);
            }
            var_dump($chapterids);

            $em = EntityManager::getInstance();

            /** @var UserRepository$userRepository */
            $userRepository = $em->getRepository(User::class);
            $userclass = $userRepository->findOneBy(['uid' => 1]);



            foreach ($chapterids as $id) {
                /** @var ChaptersRepository$chaptersRepository */
                $chaptersRepository = $em->getRepository(Chapter::class);
                $chapters = $chaptersRepository->findBy(['product' => '12']);

                $chapterid = $chapters[$id-1]->getChapterId();

                /** @var ChaptersRepository$chaptersRepository */
                $chaptersRepository = $em->getRepository(Chapter::class);
                $chapterclass = $chaptersRepository->findOneBy(['chapterId' => $chapterid]);


//                $cart_product = new CartProduct();
//                $cart_product->setChapter($chapterclass);
//                $cart_product->setUser($userclass);
//                $cart_product->setQtt(1);
//
//                $em->persist($cart_product);
//                $em->flush();
            }
        }
        AddProductToCart($cartproduct);


    }
}