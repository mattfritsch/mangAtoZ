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
use function App\getTextLangue;
use function App\isUser;
use function App\startSession;
use DateTime;

class Panier{
    public function __invoke()
    {
        startSession();

        error_reporting(E_ERROR | E_PARSE);

        $volumesName = [];
        $premierChapitres = [];
        $stockchapitre = [];
        $quantite = [];
        $chapitresmangas = [];
        $idchapvolume = [];

        $em = EntityManager::getInstance();
        if(isset($_SESSION['user'])){
            /** @var UserRepository $userRepository */
            $userRepository = $em->getRepository(User::class);
            $userclass = $userRepository->findOneBy(['uid' => $_SESSION['user']->getUid()]);

            /** @var CartProductRepository $cartproductRepository */
            $cartproductRepository = $em->getRepository(CartProduct::class);
            $cartproductclass = $cartproductRepository->findBy(['user' => $userclass]);

            if(!isset($_SESSION['cart'])){
                $_SESSION['cart'] = [];
                /** @var CartProductRepository $cartproductRepository */
                $cartproductRepository = $em->getRepository(CartProduct::class);
                $cartproductclass = $cartproductRepository->findBy(['user' => $userclass]);
                foreach($cartproductclass as $cartproduct){
                    array_push($_SESSION['cart'], [$cartproduct->getChapter()->getChapterId(), $cartproduct->getQuantite()]);
                }
            }

            if(isset($_SESSION['cart'])){
                $chapitrecartsession = [];
                $chapitrecartbdd = [];
                foreach($_SESSION['cart'] as $product){
                    /** @var ChaptersRepository $chaptersRepository */
                    $chaptersRepository = $em->getRepository(Chapter::class);
                    $chapterclass = $chaptersRepository->findOneBy(['chapterId' => $product[0]]);
                    array_push($chapitrecartsession,$chapterclass );
                }

                foreach($cartproductclass as $cartproduct){
                    array_push($chapitrecartbdd , $cartproduct->getChapter());
                }

                for($i=0; $i<count($chapitrecartsession); $i++){
                    if(in_array($chapitrecartsession[$i], $chapitrecartbdd)){
                        /** @var CartProductRepository $cartproductRepository */
                        $cartproductRepository = $em->getRepository(CartProduct::class);
                        $cartproductclass = $cartproductRepository->findOneBy(['user' => $userclass, 'chapter'=>$chapitrecartsession[$i]]);

                        $cartproduct = new CartProduct();
                        $cartproduct->setUser($userclass);
                        $cartproduct->setChapter($chapitrecartsession[$i]);
                        $cartproduct->setQuantite((int)$_SESSION['cart'][$i][1]);
                        $cartproduct->setCartTime($cartproductclass->getCartTime());

                        $em->merge($cartproduct);
                        $em->flush();
                    }

                    if(!in_array($chapitrecartsession[$i], $chapitrecartbdd)){
                        $cartproduct = new CartProduct();
                        $cartproduct->setUser($userclass);
                        $cartproduct->setChapter($chapitrecartsession[$i]);
                        $cartproduct->setQuantite((int)$_SESSION['cart'][$i][1]);
                        $cartproduct->setCartTime(new DateTime());

                        $em->persist($cartproduct);
                        $em->flush();
                    }
                }
            }

            /** @var CartProductRepository $cartproductRepository */
            $cartproductRepository = $em->getRepository(CartProduct::class);
            $cartproductclass = $cartproductRepository->findBy(['user' => $userclass]);


            foreach ($cartproductclass as $chapitre) {

                $quantite[] = $chapitre->getQuantite();

                $chapitresmangas[] = $chapitre->getChapter()->getChapterId();

                $stock = $chapitre->getChapter()->getStock();
                $stockchapitre[] = $stock;

                $nomManga = $chapitre->getChapter()->getProduct()->getProductName();

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
        }
        else {
            if (!empty($_SESSION['cart'])) {

                $chapitres = $_SESSION['cart'];

                foreach ($chapitres as $chapitre) {
                    array_push($quantite, $chapitre[1]);

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

        $args = ['lang' => getTextLangue($_SESSION['locale']), 'user' => isUser(), 'mangas'=> $manga, 'cartProduct' => $cartproductclass];
        return new Response('panier.html.twig', $args);

    }
}