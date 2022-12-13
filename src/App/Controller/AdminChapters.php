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

class AdminChapters{
    public function __invoke()
    {
        startSession();

        $lang = getTextLangue($_SESSION['locale']);

        $em = EntityManager::getInstance();

        /** @var UserRepository$userRepository */
        $userRepository = $em->getRepository(User::class);
        $user = $userRepository->findOneByEmail($_SESSION["user"]->getEmail());

        /** @var ProductRepository$productRepository */
        $productRepository = $em->getRepository(Product::class);

        /** @var ChaptersRepository$chaptersRepository */
        $chaptersRepository = $em->getRepository(Chapter::class);

        if ($user->isAdmin()){
            if ($_SESSION["user"]->getPassword() === $user->getPassword()) {
                if(!$_POST){
                    if (isset ($_GET["id"])) {
                        $id = htmlspecialchars($_GET["id"]);
                        $selectedProduct = $productRepository->findOneBy(['productId' => $id]);

                        $chapters = $chaptersRepository->findBy(['product' => $selectedProduct]);

                        return new Response('admin/adminChapters.html.twig', ['lang' => $lang, 'chapters' => $chapters, 'user' => isUser()]);
                    } else {
                        header("Location: /admin");
                    }
                } else {
                    if($_POST["method"] === "add"){
                        $currentProduct = $productRepository->findOneBy(['productId' => $_POST["productId"]]);
                        $chapters = $chaptersRepository->findBy(array(), array('chapterId' => 'DESC'));
                        $chapter = new Chapter();
                        $chapter->setProduct($currentProduct);
                        $chapter->setChapterId($chapters[0]->getChapterId()+1);
                        $chapter->setChapterName(intval($_POST["name"]));
                        $chapter->setChapterPrice(floatval($_POST["price"]));
                        $chapter->setStock(intval($_POST["stock"]));
                        $chapter->setNotAvailable(0);

                        $em->persist($chapter);
                        $em->flush();

                        $data = [];
                        $data["name"] = $chapter->getChapterName();
                        $data["price"] = $chapter->getChapterPrice();
                        $data["stock"] = $chapter->getStock();
                        $data["delete"] = $lang["ADMINCHAPTER"]["DELETE"];
                        $data["update"] = $lang["ADMINCHAPTER"]["UPDATE"];
                        if($chapter->isNotAvailable()){
                            $data["value"] = $lang["ADMINCHAPTER"]["NO"];
                        } else {
                            $data["value"] = $lang["ADMINCHAPTER"]["YES"];
                        }
                        echo(json_encode($data));
                    }
                    elseif ($_POST["method"] === "delete") {
                        $chapter = $chaptersRepository->findOneBy(["chapterId" => $_POST["chapterId"]]);
                        $chapter->setNotAvailable(!$chapter->isNotAvailable());
                        $em->persist($chapter);
                        $em->flush();

                        $isNotAvaible = $chapter->isNotAvailable();
                        $data = [];
                        if($isNotAvaible){
                            $data["value"] = $lang["ADMINCHAPTER"]["NO"];
                            $data["btn"] = $lang["ADMINCHAPTER"]["PUTBACK"];
                        } else {
                            $data["value"] = $lang["ADMINCHAPTER"]["YES"];
                            $data["btn"] = $lang["ADMINCHAPTER"]["DELETE"];
                        }

                        echo json_encode($data);
                    }
                    elseif ($_POST["method"] === "update"){
                        $currentProduct = $productRepository->findOneBy(['productId' => $_POST["productId"]]);
                        $chapter = $chaptersRepository->findOneBy(["product" => $currentProduct, "chapterName" => $_POST["chapterName"]]);
                        $chapter->setChapterPrice(floatval($_POST["price"]));
                        $chapter->setStock(intval($_POST["stock"]));

                        $em->persist($chapter);
                        $em->flush();

                        $data = [];
                        $data["name"] = $chapter->getChapterName();
                        $data["price"] = $chapter->getChapterPrice();
                        $data["stock"] = $chapter->getStock();
                        echo(json_encode($data));
                    }
                }
            }
            else {
                echo($lang["ADMINUSERS"]["ERRORPWD"]);
                die;
            }
        }
        else {
            echo($lang["ADMINUSERS"]["ERRORADMIN"]);
            die;
        }
    }
}

