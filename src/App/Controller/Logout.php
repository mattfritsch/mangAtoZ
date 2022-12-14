<?php

namespace App\Controller;

use function App\startSession;

class Logout{
    public function __invoke(){
        startSession();
        unset($_SESSION['user']);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
}
