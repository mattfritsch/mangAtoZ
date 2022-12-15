<?php

require_once dirname(__DIR__) . '/../../vendor/autoload.php';


use App\Entity\Categ;
use Framework\Doctrine\EntityManager;


function getCategoriesDesc($categorie){

    for($i=0; $i<200; $i = $i +20) {
        $json = file_get_contents('https://kitsu.io/api/edge/categories?page[limit]=20&page[offset]=' . $i);
        $arr = json_decode($json, true);
        foreach($arr['data'] as $row){
            array_push($categorie, [$row['id'], $row['attributes']['title'], $row['attributes']['description']]);
        }
    }
    $json = file_get_contents('https://kitsu.io/api/edge/categories?page[limit]=18&page[offset]=200');
    $arr = json_decode($json, true);
    foreach($arr['data'] as $row){
        array_push($categorie, [$row['id'], $row['attributes']['title'], $row['attributes']['description']]);
    }

    insertCategIntoDB($categorie);
}


function insertCategIntoDB($categorie)
{
    $em = EntityManager::getInstance();
    for ($i = 0; $i < count($categorie); $i++) {
        $categ = new Categ();

        $categ->setCategId($categorie[$i][0]);
        $categ->setCategName($categorie[$i][1]);
        $categ->setCategDesc($categorie[$i][2]);


        $em->persist($categ);
        $em->flush();


    }
}