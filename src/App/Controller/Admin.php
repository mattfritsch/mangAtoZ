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

        $language = $_SESSION["locale"];
        if($language === "en"){
            $lang = getTextLangue('trad');
        } else {
            $lang = getTextLangue('fr');;
        }

        $em = EntityManager::getInstance();

        /** @var UserRepository$userRepository */
        $userRepository = $em->getRepository(User::class);
        $user = $userRepository->findOneByEmail($_SESSION["user"]->getEmail());

        /** @var ProductRepository$productRepository */
        $productRepository = $em->getRepository(Product::class);

        if ($user->isAdmin()){
            if ($_SESSION["user"]->getPassword() === $user->getPassword()) {
                if(!$_POST){
                    $products = $productRepository->findBy(array(), array('productName' => 'ASC'));

                    return new Response('admin.html.twig', ['lang' => $lang, 'products' => $products]);
                } else {
                    $product = $productRepository->findOneBy(['productId' => $_POST["id"]]);
                    $product->setNotAvailable(!$product->isNotAvailable());
                    $em->persist($product);
                    $em->flush();
                    echo("ok");
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

