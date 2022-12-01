<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Framework\Doctrine\EntityManager;
use Framework\Response\Response;

class Connection{
    public function __invoke(): void
    {
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
//                if (password_verify($password, $user->getPassword())) {
                if ($user->getPassword() === $password) {
                    $_SESSION['user'] = $user;
                    if(array_key_exists("remember_me", $_POST)){
                        ?>
                        <script type="text/javascript">
                            localStorage.setItem('email', <?php echo json_encode($email) ?>)
                        </script>
                        <?php
                        die;
                    }
                    echo('connecté');
                    header('Location: /');
                } else {
                    echo('mot de passe incorrect');
                    $error = "Mot de passe incorrect !";
                }
            } else {
                echo('mail invalide');
                $error = "Mail non valide !";
            }
        }
        else{
            echo 'no post';
            die;
        }
    }
}
