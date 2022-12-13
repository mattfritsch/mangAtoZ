<?php

namespace App\Controller;

use function App\startSession;

class Language{
    public function __invoke()
    {
        startSession();

        $value = 'fr';

        if(isset($_POST['languageButton'])){
            if($_POST['languageSelect'] != null){
                $value = $_POST['languageSelect'];
            }
        }
        $_SESSION['locale'] = $value;

        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
}