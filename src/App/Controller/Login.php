<?php

namespace App\Controller;

use Framework\Response\Response;
use function App\getTextLangue;

class Login{
    public function __invoke()
    {
        ?>
        <script type="text/javascript">
            document.cookie="email=" + localStorage.getItem('email');
        </script>
        <?php
        $_COOKIE['email'] !== "null" ? $email = $_COOKIE['email'] : $email = "";

        $args = ['lang' => getTextLangue('en'), 'email' => $email];
        return new Response('login.html.twig', $args);
    }
}
