<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Framework\Doctrine\EntityManager;
use Framework\Response\Response;
use function App\getTextLangue;
use function App\startSession;

class Login{
    public function __invoke()
    {
        $errors = [];

        startSession();
        if(isset($_POST["email"])){
            $mail = $_POST["email"];
        } else {
            $mail = "";
        }

        $language = $_SESSION["locale"];
        if($language === "en"){
            $lang = getTextLangue('trad');
        } else {
            $lang = getTextLangue('fr');;
        }


        if (count($_POST)) {
            [
                'email' => $email,
                'password' => $password,
            ] = $_POST;

            $em = EntityManager::getInstance();

            /** @var UserRepository$userRepository */
            $userRepository = $em->getRepository(User::class);
            $user = $userRepository->findOneByEmail($email);

            if ($user !== null) {
                if (password_verify($password, $user->getPassword())) {
                    startSession();
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
                    $errors["password"] = $lang['CONNECTION']['ERRORPASSWORD'];
                }
            } else {
                $errors["email"] = $lang['CONNECTION']['ERRORMAIL'];
            }
        }

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

            if ($user != null){
                if ($password === $user->getPassword()) {
                    startSession();
                    $_SESSION['user'] = $user;
                    header('Location: /');
                }
            }
        }

        $args = ['lang' => $lang, 'errors' => $errors, 'mail' => $mail];
        return new Response('login.html.twig', $args);
    }
}
