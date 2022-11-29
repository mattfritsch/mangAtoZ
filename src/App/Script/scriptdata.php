<?php

$manga = [];
$resume = [];
$titre = [];
$image = [];
$status = [];
$categories = [];
$nmbrChapitre = [];
$rating = [];
$chapitres = [];
$id = [];
$mangafinal = [];
$m = 0;


function getData($i){
    global $m, $id, $resume, $titre, $image, $status, $nmbrChapitre, $rating, $manga, $mangafinal;
    $json = file_get_contents('https://kitsu.io/api/edge/manga?page[limit]=10&page[offset]='.$i);
    $arr = json_decode($json, true);
    foreach($arr['data'] as $row){
//        preg_match('"([^\\"]+)"', $row['id'], $result);
//        echo $result[0];
        $id[$m] = $row['id'];
        $resume[$m] = $row['attributes']['synopsis'];
        $titre[$m] = $row['attributes']['canonicalTitle'];
        $image[$m] = $row['attributes']['posterImage']['large'];
        $status[$m] = $row['attributes']['status'];
        $nmbrChapitre[$m] = $row['attributes']['chapterCount'];
        $rating[$m] = $row['attributes']['averageRating'];
        $manga[$m] = [$id[$m], $resume[$m], $titre[$m], $image[$m], $status[$m], $nmbrChapitre[$m], $rating[$m]];
        getCategories($row['id'],$m);

        getChapitres($row['id'], $m);

        if($manga[$m][5] == null && count($manga[$m][8]) >= 20) {

        }else{
            $manga[$m][5] = count($manga[$m][8]);
            array_push($mangafinal, $manga[$m]);
        }

        $m++;






    }
//    echo ($id[0]);
//    echo ($resume[0]);
//    echo ($titre[0]);
//    echo ($image[0]);
//    echo ($status[0]);
//    echo ($nmbrChapitre[0]);
//    echo ($rating[0]);
//    $manga[0] = [$id[0], $resume[0], $titre[0], $image[0], $status[0], $nmbrChapitre[0], $rating[0]];
//    array_push($mangafinal, $manga[0]);

//    var_dump($mangafinal);

//    foreach($mangafinal as $mangaa){
//        var_dump($mangaa);
//        echo '<br/>';
//        echo '<br/>';
//        echo '<br/>';
//        echo '<br/>';
//        echo '<br/>';
//    }



//    var_dump($arr['data'][0]);

//    var_dump($arr["data"][0]["id"]);



}


function getCategories($id, $m){
    global $categories, $manga;
    $categ = [];
    $json = file_get_contents('https://kitsu.io/api/edge/manga/' . $id . '/categories');
//    var_dump($json);
    $arr = json_decode($json, true);

    foreach($arr['data'] as $row){
        array_push($categ, $row['id']);
//        var_dump($categ);
//        echo '<br/>';
//        echo '<br/>';

    }

    $categories[$m] = $categ;
    array_push($manga[$m], $categories[$m]);


}

function getChapitres($id, $m){
    global $nmbrChapitre, $chapitres, $manga;
    $chap = [];
//    echo '<br/>';
//    echo $nmbrChapitre[$m];
//    echo '<br/>';
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
//                var_dump($chap);
//                echo '<br/>';
//                echo '<br/>';
            }
            $n++;

        }
        $z = $x * 20;
        $json = file_get_contents('https://kitsu.io/api/edge/manga/' . $id . '/chapters?page[limit]=20&page[offset]=' . $z);
        $arr = json_decode($json, true);
        foreach($arr['data'] as $row){
            array_push($chap, $row['id']);
//            var_dump($chap);
//            echo '<br/>';
//            echo '<br/>';
        }
    }
    elseif($nmbrChapitre[$m] == null){
        $json = file_get_contents('https://kitsu.io/api/edge/manga/' . $id . '/chapters?page[limit]=20&page[offset]=0');
        $arr = json_decode($json, true);
        foreach($arr['data'] as $row){
            array_push($chap, $row['id']);
//            var_dump($chap);
//            echo '<br/>';
//            echo '<br/>';
        }

    }
    else{
        $json = file_get_contents('https://kitsu.io/api/edge/manga/' . $id . '/chapters?page[limit]=20&page[offset]=0');
        $arr = json_decode($json, true);
        foreach($arr['data'] as $row){
            array_push($chap, $row['id']);
//            var_dump($chap);
//            echo '<br/>';
//            echo '<br/>';
        }
    }
    $chapitres[$m] = $chap;
    array_push($manga[$m], $chapitres[$m]);

}

for ( $i = 0; $i < 40; $i = $i + 10) (
getData($i)
);




//
//function filtre($obj){
//    if($obj[8] == null) {
//        return false;
//    }
//    else{
//        return true;
//    }
//}
//
//$count = count($mangafinal)-1;
//var_dump($count);
//for($u = 0; $u<$count; $u++) {
//    if ($mangafinal[$i][5] == null && count($mangafinal[$i][8]) >= 20) {
//        echo 'coucou';
//        echo '<br/>';
//        echo '<br/>';
//        echo '<br/>';
//        var_dump($mangafinal[$i]);
//        array_splice($mangafinal, $i);
//    }
//////    $result = array_filter($mangafinal[$i]);
////    print_r($result);
////    echo '<br/>';
////    echo '<br/>';
////    array_filter($mangafinal, function($value){
////        global $u;
////        return $value[$u][8] !== null;
////    });
//}

var_dump(count($mangafinal));
echo '<br/>';

echo '<br/>';
$count = 0;

foreach($mangafinal as $rows){
    $count = $count + count($rows[8]);
    var_dump($rows);
    echo '<br/>';
    echo '<br/>';
}

var_dump($count);
//array_filter($mangafinal, fn($mangafinals) => !(is_null($mangafinals)));
//foreach($mangafinal as $rows){
//    var_dump($rows[5]);
//}