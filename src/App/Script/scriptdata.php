<?php
require_once dirname(__DIR__) . '/../../vendor/autoload.php';


use App\Entity\Product;
use App\Entity\Chapter;
use Framework\Doctrine\EntityManager;

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

function getData($i){
    global $m, $id, $resume, $titre, $image, $status, $nmbrChapitre, $rating, $manga, $mangafinal;
    $mangafinal = [];
    $json = file_get_contents('https://kitsu.io/api/edge/manga?page[limit]=10&page[offset]='.$i);
    $arr = json_decode($json, true);
    foreach($arr['data'] as $row){
        $id[$m] = (int) $row['id'];
        $resume[$m] = $row['attributes']['synopsis'];
        $titre[$m] = $row['attributes']['canonicalTitle'];
        $image[$m] = $row['attributes']['posterImage']['large'];
        $status[$m] = $row['attributes']['status'];
        $nmbrChapitre[$m] = $row['attributes']['chapterCount'];
        $rating[$m] = (float) $row['attributes']['averageRating'];
        $agerating[$m] = $row['attributes']['ageRating'];
        $manga[$m] = [$id[$m], $resume[$m], $titre[$m], $image[$m], $status[$m], $nmbrChapitre[$m], $rating[$m], $agerating[$m]];
        getCategories($row['id'],$m);

        getChapitres($row['id'], $m);

        if($manga[$m][4] == 'finished'){
            $manga[$m][4] = true;
        }
        else{
            $manga[$m][4] = false;
        }

        if($manga[$m][7] == 'G' || $manga[$m][4] == null){
            $manga[$m][7] = true;
        }
        else{
            $manga[$m][7] = false;
        }

        if($manga[$m][5] == null && count($manga[$m][9]) >= 20) {

        }else{
            $manga[$m][5] = count($manga[$m][9]);
            array_push($mangafinal, $manga[$m]);
        }
        $m++;
    }
    insertIntoDB();
    echo '<br/>';
    echo '<br/>';
}


function getCategories($id, $m){
    global $categories, $manga;
    $categ = [];
    $json = file_get_contents('https://kitsu.io/api/edge/manga/' . $id . '/categories');
//    var_dump($json);
    $arr = json_decode($json, true);

    foreach($arr['data'] as $row){
        array_push($categ, (int) $row['id']);
    }
    $categories[$m] = $categ;
    array_push($manga[$m], $categories[$m]);
}

function getChapitres($id, $m){
    global $nmbrChapitre, $chapitres, $manga;
    $chap = [];
    if($nmbrChapitre[$m] > 20) {
        $x = intdiv($nmbrChapitre[$m], 20);
        $y = $nmbrChapitre[$m] % 20;
        $n = 0;
        while($n < $x) {
            $a = $n*20;
            $json = file_get_contents('https://kitsu.io/api/edge/manga/' . $id . '/chapters?page[limit]=20&page[offset]=' . $a);
            $arr = json_decode($json, true);
            foreach($arr['data'] as $row){
                array_push($chap, $row['id']);
            }
            $n++;
        }
        $z = $x * 20;
        $json = file_get_contents('https://kitsu.io/api/edge/manga/' . $id . '/chapters?page[limit]=20&page[offset]=' . $z);
        $arr = json_decode($json, true);
        foreach($arr['data'] as $row){
            array_push($chap, $row['id']);
        }
    }
    elseif($nmbrChapitre[$m] == null){
        $json = file_get_contents('https://kitsu.io/api/edge/manga/' . $id . '/chapters?page[limit]=20&page[offset]=0');
        $arr = json_decode($json, true);
        foreach($arr['data'] as $row){
            array_push($chap, $row['id']);
        }
    }
    else{
        $json = file_get_contents('https://kitsu.io/api/edge/manga/' . $id . '/chapters?page[limit]=20&page[offset]=0');
        $arr = json_decode($json, true);
        foreach($arr['data'] as $row){
            array_push($chap, $row['id']);
        }
    }
    $chapitres[$m] = $chap;
    array_push($manga[$m], $chapitres[$m]);
}





//$count = 0;
//
//foreach($mangafinal as $rows){
//    $count = $count + count($rows[8]);
////    var_dump($rows);
////    echo '<br/>';
////    echo '<br/>';
//}

//var_dump($count);
//$categid = [];
//foreach($mangafinal[0][8] as $value){
//    array_push($categid, $value);
//    print($value);
//}
//var_dump($mangafinal);
//var_dump($categid);

function insertIntoDB()
{
    global $mangafinal;
    $em = EntityManager::getInstance();
    for ($i = 0; $i < count($mangafinal); $i++) {

        var_dump($mangafinal[$i][0]);
        $product = new Product();

        $product->setProductId($mangafinal[$i][0]);
        $product->setResume($mangafinal[$i][1]);
        $product->setProductName($mangafinal[$i][2]);
        $product->setImg($mangafinal[$i][3]);
        $product->setStatus($mangafinal[$i][4]);
        $product->setChapterNumber($mangafinal[$i][5]);
//        $product->setCateg($mangafinal[$i][8][0]);
        $product->setAverageRating($mangafinal[$i][6]);
        $product->setAgeRank($mangafinal[$i][7]);


        $em->persist($product);
        $em->flush();

        $em2 = EntityManager::getInstance();

        for($j = 0; $j < count($mangafinal[$i][9]); $j++) {
            $chapter = new Chapter();

            $stock = rand(0, 10);
            $entier = rand(1, 9);
            $decimale = rand(1, 99);
            $prix = $entier.'.'.$decimale;

            $chapter->setProduct();
            $chapter->setChapterId($mangafinal[$i][9][$j]);
            $chapter->setStock($stock);
            $chapter->setChapterPrice($prix);

            $em2->persist($chapter);
            $em2->flush();
        }


    }
}
//
//        /** @var \App\Repository\ProductRepository$productRepository */
//      $productRepository = $em->getRepository(Product::class);
//      // $users = $userRepository->findAll();
//      $product = $productRepository->findOneBy(['productId'=>'1']);
//      var_dump($product);
//      die;