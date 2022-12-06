<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Framework\Doctrine\EntityManager;
use Framework\Response\Response;
use function App\getTextLangue;

class Login{
    public function __invoke()
    {

        $email = "";
        $password = "";

        if(isset($_COOKIE['email'])) {
            $_COOKIE['email'] !== "null" ? $email = $_COOKIE['email'] : $email = "";
        }
        if(isset($_COOKIE['password'])) {
            $_COOKIE['password'] !== "null" ? $password = $_COOKIE['password'] : $password = "";
        }

        if ($email !== "" && $password !== ""){

            $em = EntityManager::getInstance();

            /** @var UserRepository$userRepository */
            $userRepository = $em->getRepository(User::class);
            $user = $userRepository->findOneByEmail($email);

            if ($password === $user->getPassword()) {
                if(session_id() == ''){
                    session_start();
                }
                $_SESSION['user'] = $user;
                header('Location: /');
            }
        }

        if(isset($_SESSION["errors"])){
            $errors = $_SESSION["errors"];
        }
        else{
            $errors = "";
        }


        $args = ['lang' => getTextLangue('fr'), 'errors' => $errors];
        return new Response('login.html.twig', $args);
    }
}
