<?php

namespace App\Controller;


use App\Entity\CartProduct;
use App\Entity\Chapter;
use App\Entity\User;
use App\Repository\CartProductRepository;
use App\Repository\ChaptersRepository;
use App\Repository\UserRepository;
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
        var_dump($_SESSION['cart']);
        echo'<br/>';
        $volumesName = [];
        var_dump($volumesName);
        $premierChapitres = [];
        $stockchapitre = [];
        $quantite = [];
        $chapitresmangas = [];
        $idchapvolume = [];

        foreach($_SESSION['cart'] as $product){
    var_dump($product);
    echo'<br/>';

}
        $em = EntityManager::getInstance();
//        var_dump($_SESSION['cart']);
        if(isset($_SESSION['user'])){
            /** @var UserRepository $userRepository */
            $userRepository = $em->getRepository(User::class);
            $userclass = $userRepository->findOneBy(['uid' => $_SESSION['user']->getUid()]);

            /** @var CartProductRepository $cartproductRepository */
            $cartproductRepository = $em->getRepository(CartProduct::class);
            $cartproductclass = $cartproductRepository->findBy(['user' => $userclass]);
//            var_dump($cartproductclass);


            var_dump($_SESSION['cart']);
            if(isset($_SESSION['cart'])){
                $chapitrecartsession = [];
                $chapitrecartbdd = [];
                foreach($_SESSION['cart'] as $product){
                    /** @var ChaptersRepository $chaptersRepository */
                    $chaptersRepository = $em->getRepository(Chapter::class);
                    $chapterclass = $chaptersRepository->findBy(['chapterId' => $product[0]]);
                    array_push($chapitrecartsession,$chapterclass );
                }
//                var_dump($chapitrecartsession);
                foreach($cartproductclass as $cartproduct){
                    array_push($chapitrecartbdd , $cartproduct->getChapter());
                }
echo '<br/>';
//                var_dump($userclass);


                foreach($chapitrecartsession as $chapitresession){
                    if(!in_array($chapitresession[0], $chapitrecartbdd)){
//                        var_dump($chapitresession);
                        $cartproduct = new CartProduct();
                        $cartproduct->setUser($userclass);
                        $cartproduct->setChapter($chapitresession[0]);
                        $cartproduct->setQuantite(1);

                        $em->persist($cartproduct);
                        $em->flush();

                    }
                    for($i=0; $i<count($_SESSION['cart']); $i++){
                        /** @var ChaptersRepository $chaptersRepository */
                        $chaptersRepository = $em->getRepository(Chapter::class);
                        $chapterclass = $chaptersRepository->findOneBy(['chapterId' => $_SESSION['cart'][$i][0]]);
//                        var_dump($chapterclass);
                        if(in_array($chapterclass, $chapitrecartbdd)){
                            /** @var CartProductRepository $cartproductRepository */
                            $cartproductRepository = $em->getRepository(CartProduct::class);
                            $cartproductclass = $cartproductRepository->findOneBy(['user' => $userclass, 'chapter'=>$chapterclass]);
                            $_SESSION['cart'][$i][1] = $cartproductclass->getQuantite();
                            echo'<br/>';

//                            var_dump($product[1]);
                        }
                    }
                }
                echo'<br/>';
                echo'<br/>';
                    var_dump($_SESSION['cart']);
                echo'<br/>';
                echo'<br/>';

            }

            /** @var CartProductRepository $cartproductRepository */
            $cartproductRepository = $em->getRepository(CartProduct::class);
            $cartproductclass = $cartproductRepository->findBy(['user' => $userclass]);


            foreach ($cartproductclass as $chapitre) {
//                var_dump($chapitre->getChapter()->getStock());
                $quantite[] = $chapitre->getQuantite();

                var_dump($chapitre->getChapter()->getProduct()->getProductId());
                $chapitresmangas[] = $chapitre->getChapter()->getChapterId();


                $idvolume = $chapitre->getChapter()->getProduct()->getProductId();

//
                $stock = $chapitre->getChapter()->getStock();
                $stockchapitre[] = $stock;
//
//                /** @var ChaptersRepository $chaptersRepository */
//                $chaptersRepository = $em->getRepository(Chapter::class);
//                $allchapters = $chaptersRepository->findBy(['product' => $idvolume]);
//
//                $firstchaptervolume = $allchapters[0]->getChapterId();
//
//                if (!in_array($firstchaptervolume, $premierChapitres)) {
//                    array_push($premierChapitres, $firstchaptervolume);
//                }
//
                $nomManga = $chapitre->getChapter()->getProduct()->getProductName();
                var_dump($volumesName);
                if (!in_array($nomManga, $volumesName)) {
                    array_push($volumesName, $nomManga);
                }

                foreach ($volumesName as $volumeName) {
                    $volumesChapitres[$volumeName] = [];
                    $idchapvolume[$volumeName] = [];
                    foreach ($cartproductclass as $chapitre) {
                        $chaptersRepository = $em->getRepository(Chapter::class);
                        $chapterclass = $chaptersRepository->findOneBy(['chapterId' => $chapitre->getChapter()->getChapterId()]);

                        $nomManga = $chapterclass->getProduct()->getProductName();

                        if ($nomManga == $volumeName) {
                            array_push($volumesChapitres[$volumeName], $chapitre->getChapter()->getChapterId());
                            array_push($idchapvolume[$volumeName], $chapterclass->getChapterName());
                        }
                    }
                }

                $quantitechapitre = [];
                for ($i = 0; $i < count($quantite); $i++) {
                    $quantitechapitre[$i] = $quantite[$i];
                }

                $k = 0;
                $stocks = [];
                $chapitresmanga = [];
                for ($i = 0; $i < count($volumesChapitres); $i++) {
                    for ($j = 0; $j < count($volumesChapitres[$volumesName[$i]]); $j++) {
                        $chapitresmanga[$i][] = $chapitresmangas[$k];
                        $quantitechapitre = $quantite[$k];
                        $stocks[$i][] = [$stockchapitre[$k], $quantitechapitre];
                        $k = $k + 1;
                    }
                }
                $manga = [];
                for ($i = 0; $i < count($volumesName); $i++) {
                    $manga[$i] = [$volumesName[$i], $idchapvolume[$volumesName[$i]], $chapitresmanga[$i], $stocks[$i]];
                }
            }
            var_dump($quantite);


        }
        else {
            if (!empty($_SESSION['cart'])) {


                $chapitres = $_SESSION['cart'];


                foreach ($chapitres as $chapitre) {
                    array_push($quantite, $chapitre[1]);

                    var_dump($chapitre[0]);
                    array_push($chapitresmangas, $chapitre[0]);

                    /** @var ChaptersRepository $chaptersRepository */
                    $chaptersRepository = $em->getRepository(Chapter::class);
                    $chapterclass = $chaptersRepository->findOneBy(['chapterId' => $chapitre[0]]);

                    $idvolume = $chapterclass->getProduct()->getProductId();

                    $stock = $chapterclass->getStock();
                    array_push($stockchapitre, $stock);

                    /** @var ChaptersRepository $chaptersRepository */
                    $chaptersRepository = $em->getRepository(Chapter::class);
                    $allchapters = $chaptersRepository->findBy(['product' => $idvolume]);

                    $firstchaptervolume = $allchapters[0]->getChapterId();

                    if (!in_array($firstchaptervolume, $premierChapitres)) {
                        array_push($premierChapitres, $firstchaptervolume);
                    }

                    $nomManga = $chapterclass->getProduct()->getProductName();

                    if (!in_array($nomManga, $volumesName)) {
                        array_push($volumesName, $nomManga);
                    }
                }

                foreach ($volumesName as $volumeName) {
                    $volumesChapitres[$volumeName] = [];
                    $idchapvolume[$volumeName] = [];
                    foreach ($chapitres as $chapitre) {
                        $chaptersRepository = $em->getRepository(Chapter::class);
                        $chapterclass = $chaptersRepository->findOneBy(['chapterId' => $chapitre[0]]);

                        $nomManga = $chapterclass->getProduct()->getProductName();

                        if ($nomManga == $volumeName) {
                            array_push($volumesChapitres[$volumeName], $chapitre[0]);
                            array_push($idchapvolume[$volumeName], $chapterclass->getChapterName());
                        }
                    }
                }

                $quantitechapitre = [];
                for ($i = 0; $i < count($quantite); $i++) {
                    $quantitechapitre[$i] = $quantite[$i];
                }

                $k = 0;
                $stocks = [];
                $chapitresmanga = [];
                for ($i = 0; $i < count($volumesChapitres); $i++) {
                    for ($j = 0; $j < count($volumesChapitres[$volumesName[$i]]); $j++) {
                        $chapitresmanga[$i][] = $chapitresmangas[$k];
                        $quantitechapitre = $quantite[$k];
                        $stocks[$i][] = [$stockchapitre[$k], $quantitechapitre];
                        $k = $k + 1;
                    }
                }

                $manga = [];
                for ($i = 0; $i < count($volumesName); $i++) {
                    $manga[$i] = [$volumesName[$i], $idchapvolume[$volumesName[$i]], $chapitresmanga[$i], $stocks[$i]];
                }
            }
        }

        $args = ['lang' => getTextLangue($_SESSION['locale']), 'user' => isUser(), 'mangas'=>$manga];
        return new Response('panier.html.twig', $args);

    }
}