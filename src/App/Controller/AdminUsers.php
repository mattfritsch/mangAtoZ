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
        $lang = getTextLangue($_SESSION["locale"]);

        $em = EntityManager::getInstance();

        /** @var UserRepository$userRepository */
        $userRepository = $em->getRepository(User::class);
        $user = $userRepository->findOneByEmail($_SESSION["user"]->getEmail());

        if ($user->isAdmin()){
            if ($_SESSION["user"]->getPassword() === $user->getPassword()) {
                if(!$_POST){
                    $users = $userRepository->findBy(array(), array('email' => 'ASC'));

                    return new Response('admin/adminUsers.html.twig', ['lang' => $lang, 'users' => $users]);
                } else {
                    if($_POST["mail"] !== $_SESSION["user"]->getEmail()){
                        $user = $userRepository->findOneByEmail($_POST["mail"]);
                        $user->setAdmin(!$user->isAdmin());
                        $em->persist($user);
                        $em->flush();

                        $isAdmin = $user->isAdmin();
                        $data = [];
                        $data["response"] = "ok";
                        if($isAdmin){
                            $data["value"] = $lang["ADMINUSERS"]["YES"];
                            $data["btn"] = $lang["ADMINUSERS"]["DELETE"];
                        } else {
                            $data["value"] = $lang["ADMINUSERS"]["NO"];
                            $data["btn"] = $lang["ADMINUSERS"]["ADD"];
                        }
                    }
                    else{
                        $data = [];
                        $data["response"] = "error";
                        $data["error"] = $lang["ADMINUSERS"]["ERRORAUTO"];
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

