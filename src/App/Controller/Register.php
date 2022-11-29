<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Framework\Doctrine\EntityManager;
use Framework\Response\Response;

class Register{
    public function __invoke(): void
    {
        if (count($_POST)) {
            [
                'email' => $email,
                'password' => $password,
            ] = $_POST;

            var_dump($_POST);

//            $error = null;

//            if ($user !== null) {
////                if (password_verify($password, $user->getPassword())) {
//                if ($user->getPassword() === $password) {
//                    $_SESSION['user'] = $user;
//                    echo('connecté');
//                    header('Location: /');
//                } else {
//                    echo('mot de passe incorrect');
//                    $error = "Mot de passe incorrect !";
//                }
//            } else {
//                echo('mail invalide');
//                $error = "Mail non valide !";
//            }
        }
        else{
            echo 'no post';
            die;
        }
    }
}
