<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Framework\Doctrine\EntityManager;
use Framework\Response\Response;

class Connection{
    public function __invoke(): void
    {
        $_SESSION["errors"] = '';

        if (count($_POST)) {
            [
                'email' => $email,
                'password' => $password,
            ] = $_POST;

            $em = EntityManager::getInstance();

            /** @var UserRepository$userRepository */
            $userRepository = $em->getRepository(User::class);
            $user = $userRepository->findOneByEmail($email);

            $error = null;

            if ($user !== null) {
                if (password_verify($password, $user->getPassword())) {
                    if(session_id() == ''){
                        session_start();
                    }
                    $_SESSION['user'] = $user;
                    if(array_key_exists("remember_me", $_POST)){
                        ?>
                        <script type="text/javascript">
                            localStorage.setItem('email', <?php echo json_encode($email) ?>)
                            localStorage.setItem('password', <?php echo json_encode($user->getPassword()) ?>)
                        </script>
                        <?php
                    }
                    ?>
                    <script type="text/javascript">
                        window.location.href = "/";
                    </script>
                    <?php
                    die;
//                    header('Location: /');
                } else {
                    $error = ["password" => "Mot de passe incorrect !"];
                    $_SESSION["errors"] = $error;
                    header('Location: /login');
                }
            } else {
                $error = ["mail" => "Mail non valide !"];
                $_SESSION["errors"] = $error;
                header('Location: /login');
            }
        }
        else{
            echo 'no post';
            die;
        }
    }
}
