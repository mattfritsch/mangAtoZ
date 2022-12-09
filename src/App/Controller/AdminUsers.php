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

class AdminUsers{
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

        if ($user->isAdmin()){
            if ($_SESSION["user"]->getPassword() === $user->getPassword()) {
                if(!$_POST){
                    $users = $userRepository->findBy(array(), array('email' => 'ASC'));

                    return new Response('adminUsers.html.twig', ['lang' => $lang, 'users' => $users]);
                } else {
                    if($_POST["mail"] !== $_SESSION["user"]->getEmail()){
                        $user = $userRepository->findOneByEmail($_POST["mail"]);
                        $user->setAdmin(!$user->isAdmin());
                        $em->persist($user);
                        $em->flush();
                        echo("ok");
                    }
                    else{
                        echo($lang["ADMINUSERS"]["ERRORAUTO"]);
                    }
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

