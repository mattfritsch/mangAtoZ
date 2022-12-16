<?php
require_once dirname(__DIR__) . '/../../vendor/autoload.php';

use App\Controller\EntryClass;
use App\Entity\Categ;
use App\Entity\CartProduct;
use App\Repository\CartProductRepository;
use Framework\Doctrine\EntityManager;

$em = EntityManager::getInstance();

/** @var CartProductRepository$cartproductRepository */
$cartproductRepository = $em->getRepository(CartProduct::class);
$cartproductclass = $cartproductRepository->findAll();

$time = new DateTime();



foreach($cartproductclass as $cartproduct){
    $timeproduct = $cartproduct->getCartTime();
    $timeproduct->modify("+5 minutes");

    if($time>$timeproduct){
        $cartproductnewquantite = new CartProduct();
            $cartproductnewquantite->setQuantite($cartproduct->getQuantite());
            $cartproductnewquantite->setChapter($cartproduct->getChapter());
            $cartproductnewquantite->setUser($cartproduct->getUser());
            $entity = $em->merge($cartproductnewquantite);
            $em->remove($entity);
            $em->flush();
    }


}