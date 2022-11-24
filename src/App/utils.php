<?php

namespace App;

function getTextLangue(string $language, string $file){
    $fr = require_once dirname(dirname(__DIR__)). '/locale/fr.php';
    $en = require_once dirname(dirname(__DIR__)). '/locale/en.php';

    if ($language === 'fr' ){
        $l = $fr;
    }
    else{
        $l = $en;
    }

    return $l[$file];
}