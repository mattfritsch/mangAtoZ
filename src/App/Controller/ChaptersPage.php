<?php

namespace App\Controller;

use App\Entity\Chapter;
use App\Entity\Product;
use App\Entity\User;
use App\Repository\ChaptersRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Framework\Doctrine\EntityManager;
use Framework\Response\Response;
use function App\getTextLangue;
use function App\isUser;
use function App\startSession;

class ChaptersPage
{

    public function __invoke()
    {
        startSession();

        $lang = getTextLangue($_SESSION['locale']);


        $em = EntityManager::getInstance();

        /** @var ChaptersRepository$chaptersRepository */
        $chaptersRepository = $em->getRepository(Chapter::class);
        /** @var ProductRepository$productRepository */
        $productRepository = $em->getRepository(Product::class);

        /** @var UserRepository$userRepository */
        $userRepository = $em->getRepository(User::class);
        $user = $userRepository->findOneByEmail($_SESSION["user"]->getEmail());

        if (isset ($_GET["id"])) {
            $id = htmlspecialchars($_GET["id"]);
            $chapters = $chaptersRepository->findBy(['product' => $id]);
            $product = $productRepository->findBy(['productId' => $id]);
            if(isset($_POST)){
                if(isset($_POST["method"])){
                    if($_POST["method"] === "notify") {
                        $chapter = $chaptersRepository->findOneBy(['chapterId' => $_POST["chapterId"]]);
                        $users = $chapter->getEmail();

                        $isAlreadyNotify = false;
                        foreach ($users as $thisUser){
                            if($thisUser->getUid() === $user->getUid())
                                $isAlreadyNotify = true;
                        }

                        $data = [];

                        if(!$isAlreadyNotify){
                            $users[] = $user;
                            $chapter->setEmail($users);
                            $em->persist($chapter);
                            $em->flush();


                            $data["response"] = "success";
                            $data["msg"] = $lang["CHAPITRE"]["MSGSUCCESS"];

                        } else {
                            $data["response"] = "error";
                            $data["msg"] = $lang["CHAPITRE"]["MSGERROR"];

                        }
                        echo json_encode($data);
                    }
                } else {
                    $_SESSION['productid'] = $id;

                    $notAvailable = 0;
                    foreach ($chapters as $chapter){
                        if ($chapter->getStock() === 0){
                            $notAvailable = 1;
                            break;
                        }
                    }

                    $args = ['lang' => $lang, 'chapters' =>$chapters, 'product' => $product,
                        'user' => isUser(), 'notAvailable' => $notAvailable];
                    return new Response('chapterspage.html.twig', $args);
                }
            }
        }
    }

}