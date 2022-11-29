<?php

namespace App;

function getTextLangue(string $language){
    $fr = require_once dirname(dirname(__DIR__)). '/locale/fr.php';
    $en = require_once dirname(dirname(__DIR__)). '/locale/en.php';

    if ($language === 'fr' ){
        $l = $fr;
    }
    else{
        $l = $en;
    }

    return $l;
}

function displayErrors(array $errors, string $field): void {
    foreach($errors[$field] ?? [] as $error) {
        echo sprintf('<div class="invalid-feedback">%s</div>', $error);
    }
}