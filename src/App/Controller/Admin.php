<?php
namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Framework\Doctrine\EntityManager;
use Framework\Response\Response;
use function App\getTextLangue;
use function App\startSession;

class Admin{
    public function __invoke()
    {
        startSession();
        $em = EntityManager::getInstance();

        /** @var UserRepository$userRepository */
        $userRepository = $em->getRepository(User::class);
        $user = $userRepository->findOneByEmail($_SESSION["user"]->getEmail());

        if ($user->isAdmin()){
            if ($_SESSION["user"]->getPassword() === $user->getPassword()) {
                /** @var ProductRepository$productRepository */
                $productRepository = $em->getRepository(Product::class);
                $products = $productRepository->findBy(array(), array('productName' => 'ASC'));

                return new Response('admin.html.twig', ['lang' => getTextLangue('trad'), 'products' => $products]);
            }
            else {
                echo 'Bien essayé, mais non';
                die;
            }
        }
        else {
            echo 'Non non, ça ne marchera pas petit malin';
            die;
        }
    }
}

