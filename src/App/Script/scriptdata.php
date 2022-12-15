<?php
require_once dirname(__DIR__) . '/../../vendor/autoload.php';


use App\Entity\Product;
use App\Entity\Chapter;
use App\Entity\ProductCateg;
use App\Entity\Categ;
use App\Entity\User;
use App\Repository\ProductRepository;
use App\Repository\CategRepository;
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

function getData($i,$m, $id, $resume, $titre, $image, $status, $nmbrChapitre, $rating, $manga, $mangafinal, $categories, $agerating,$chapitres){
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
        $categmanga = getCategories($row['id'],$m,$categories,$manga);
        array_push($manga[$m], $categmanga);
        $chapitremanga = getChapitres($row['id'], $m,$nmbrChapitre, $chapitres);
        array_push($manga[$m], $chapitremanga);
        var_dump($manga[$m]);
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
    insertIntoDB($mangafinal);
}


function getCategories($id, $m,$categories, $manga){
    $categ = [];
    $json = file_get_contents('https://kitsu.io/api/edge/manga/' . $id . '/categories');
    $arr = json_decode($json, true);

    foreach($arr['data'] as $row){
        array_push($categ, (int) $row['id']);
    }
    $categories[$m] = $categ;
    return $categories[$m];

}

function getChapitres($id, $m,$nmbrChapitre, $chapitres){

    $chap = [];
    if($nmbrChapitre[$m] > 20) {
        $x = intdiv($nmbrChapitre[$m], 20);
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
    return $chapitres[$m];
}


function insertIntoDB($mangafinal)
{
    $em = EntityManager::getInstance();
    for ($i = 0; $i < count($mangafinal); $i++) {

        var_dump($mangafinal[$i][3]);
        $product = new Product();

        $product->setProductId($mangafinal[$i][0]);
        $product->setResume($mangafinal[$i][1]);
        $product->setProductName($mangafinal[$i][2]);
        if($mangafinal[$i][3] === null){
            $product->setImg("pas d'image");
        }else {
            $product->setImg($mangafinal[$i][3]);
        }
        $product->setStatus($mangafinal[$i][4]);
        $product->setChapterNumber($mangafinal[$i][5]);
        $categmangaclass = [];
        foreach($mangafinal[$i][8] as $coucou){
            /** @var CategRepository$categRepository */
            $categRepository = $em->getRepository(Categ::class);
            $categclass = $categRepository->findOneBy(['categId' => $coucou]);
            var_dump($categclass);
            array_push($categmangaclass, $categclass);
        }
        $product->setCategories($categmangaclass);
        $product->setAverageRating($mangafinal[$i][6]);
        $product->setAgeRank($mangafinal[$i][7]);
        $product->setNotAvailable(0);


        $em->persist($product);
        $em->flush();

        /** @var ProductRepository$productRepository */
        $productRepository = $em->getRepository(Product::class);
        $productclass = $productRepository->findOneBy(['productId' => $mangafinal[$i][0]]);

        $em2 = EntityManager::getInstance();

        $idchapitrevolume = 1;

        for($j = 0; $j < count($mangafinal[$i][9]); $j++) {
            $chapter = new Chapter();

            $stock = rand(0, 10);
            $entier = rand(1, 9);
            $decimale = rand(1, 99);
            $prix = $entier.'.'.$decimale;

            $chapter->setProduct($productclass);
            $chapter->setChapterId($mangafinal[$i][9][$j]);
            $chapter->setStock($stock);
            $chapter->setChapterPrice($prix);
            $chapter->setChapterName($idchapitrevolume);
            $chapter->setNotAvailable(0);

            $em2->persist($chapter);
            $em2->flush();
            $idchapitrevolume = $idchapitrevolume +1;
        }
    }
}
