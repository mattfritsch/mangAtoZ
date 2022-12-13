<?php

namespace App\Controller;


use App\Entity\CartProduct;
use App\Entity\Chapter;
use App\Repository\ChaptersRepository;
use Framework\Doctrine\EntityManager;
use Framework\Response\Response;
use function App\clearCart;
use function App\getTextLangue;
use function App\isUser;
use function App\startSession;



class Panier{
    public function __invoke()
    {
        startSession();
//        clearCart();
foreach($_SESSION['cart'] as $product){
    var_dump($product);
    echo'<br/>';
}
//        var_dump($_SESSION['cart']);

        $em = EntityManager::getInstance();
        $chapitres = $_SESSION['cart'];

        $volumesName = [];
        $premierChapitres= [];
        $stockchapitre = [];
        $quantite = [];
        $chapitresmangas = [];
        $idchapvolume = [];

        foreach ($chapitres as $chapitre){
            array_push($quantite,$chapitre[1]);

            var_dump($chapitre[0]);
            array_push($chapitresmangas, $chapitre[0]);

            /** @var ChaptersRepository$chaptersRepository */
            $chaptersRepository = $em->getRepository(Chapter::class);
            $chapterclass = $chaptersRepository->findOneBy(['chapterId' => $chapitre[0]]);

            $idvolume = $chapterclass->getProduct()->getProductId();

            $stock = $chapterclass->getStock();
            array_push($stockchapitre, $stock);

            /** @var ChaptersRepository$chaptersRepository */
            $chaptersRepository = $em->getRepository(Chapter::class);
            $allchapters = $chaptersRepository->findBy(['product' => $idvolume]);

            $firstchaptervolume = $allchapters[0]->getChapterId();

            if(!in_array($firstchaptervolume, $premierChapitres)) {
                array_push($premierChapitres, $firstchaptervolume);
            }

            $nomManga = $chapterclass->getProduct()->getProductName();

            if(!in_array($nomManga, $volumesName)){
                array_push($volumesName, $nomManga);
            }
        }

        foreach ($volumesName as $volumeName){
            $volumesChapitres[$volumeName] = [];
            $idchapvolume[$volumeName] = [];
            foreach($chapitres as $chapitre){
                $chaptersRepository = $em->getRepository(Chapter::class);
                $chapterclass = $chaptersRepository->findOneBy(['chapterId' => $chapitre[0]]);

                $nomManga = $chapterclass->getProduct()->getProductName();

                if($nomManga == $volumeName){
                    array_push($volumesChapitres[$volumeName], $chapitre[0]);
                    array_push($idchapvolume[$volumeName], $chapterclass->getChapterName());
                }
            }
        }

        $quantitechapitre = [];
        for($i=0; $i<count($quantite); $i++){
            $quantitechapitre[$i] = $quantite[$i];
        }

        $k=0;
        $stocks = [];
        $chapitresmanga = [];
        for($i=0; $i<count($volumesChapitres); $i++){
            for($j=0; $j<count($volumesChapitres[$volumesName[$i]]); $j++){
                $chapitresmanga[$i][] = $chapitresmangas[$k];
                $quantitechapitre = $quantite[$k];
                $stocks[$i][] = [$stockchapitre[$k], $quantitechapitre];
                $k = $k+1;
            }
        }

        $manga = [];
        for($i = 0; $i<count($volumesName); $i++){
            $manga[$i] = [$volumesName[$i], $idchapvolume[$volumesName[$i]], $chapitresmanga[$i], $stocks[$i]];
        }

        $args = ['lang' => getTextLangue($_SESSION['locale']), 'user' => isUser(), 'mangas'=>$manga];
        return new Response('panier.html.twig', $args);

    }
}