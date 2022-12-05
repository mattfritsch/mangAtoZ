<?php

require_once dirname(__DIR__) . '/../../vendor/autoload.php';


use App\Entity\Categ;
use Framework\Doctrine\EntityManager;

$categories = [];
function getCategoriesDesc(){
    global $categories;

    for($i=0; $i<200; $i = $i +20) {
        $json = file_get_contents('https://kitsu.io/api/edge/categories?page[limit]=20&page[offset]=' . $i);
        $arr = json_decode($json, true);
        foreach($arr['data'] as $row){
            array_push($categories, [$row['id'], $row['attributes']['title'], $row['attributes']['description']]);
        }
    }
    $json = file_get_contents('https://kitsu.io/api/edge/categories?page[limit]=18&page[offset]=200');
    $arr = json_decode($json, true);
    foreach($arr['data'] as $row){
        array_push($categories, [$row['id'], $row['attributes']['title'], $row['attributes']['description']]);
    }
    var_dump($categories);

    insertCategIntoDB();
}


function insertCategIntoDB()
{
    global $categories;
    $em = EntityManager::getInstance();
    for ($i = 0; $i < count($categories); $i++) {
        $categ = new Categ();

        $categ->setCategId($categories[$i][0]);
        if($categories[$i][1] != null){
            $categ->setCategName($categories[$i][1]);
        }else{

        }

        $categ->setCategDesc($categories[$i][2]);


        $em->persist($categ);
        $em->flush();


    }
}