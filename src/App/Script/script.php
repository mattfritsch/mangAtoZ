<?php
include('scriptdata.php');
include('scriptCategories.php');
$categorie = [];
getCategoriesDesc($categorie);

for($i = 0; $i<10; $i = $i+10) {
    $manga = [];
    $resume = [];
    $titre = [];
    $image = [];
    $status = [];
    $categories = [];
    $nmbrChapitre = [];
    $rating = [];
    $agerating = [];
    $chapitres = [];
    $id = [];
    $mangafinal = [];
    $m = 0;
    getData($i,$m, $id, $resume, $titre, $image, $status, $nmbrChapitre, $rating, $manga, $mangafinal, $categories, $agerating,$chapitres);
}

