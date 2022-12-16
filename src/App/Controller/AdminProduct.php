<?php
namespace App\Controller;

use App\Entity\Categ;
use App\Entity\Product;
use App\Entity\User;
use App\Repository\CategRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Framework\Doctrine\EntityManager;
use Framework\Response\Response;
use function App\getTextLangue;
use function App\isUser;
use function App\startSession;

class AdminProduct{
    public function __invoke()
    {
        startSession();

        $lang = getTextLangue($_SESSION["locale"]);

        if (isset($_SESSION["user"])) {
            $em = EntityManager::getInstance();

            /** @var UserRepository $userRepository */
            $userRepository = $em->getRepository(User::class);
            $user = $userRepository->findOneByEmail($_SESSION["user"]->getEmail());

            /** @var ProductRepository $productRepository */
            $productRepository = $em->getRepository(Product::class);

            /** @var CategRepository $categRepository */
            $categRepository = $em->getRepository(Categ::class);
            $categs = $categRepository->findBy(array(), array("categName" => "ASC"));


            if ($user->isAdmin()) {
                if ($_SESSION["user"]->getPassword() === $user->getPassword()) {
                    //MODIF
                    if (isset ($_GET["id"])) {
                        $id = $_GET["id"];
                        $selectedProduct = $productRepository->findOneBy(['productId' => $id]);
                        if (!$_POST) {
                            return new Response('admin/adminProduct.html.twig', ['lang' => $lang, 'selectedProduct' => $selectedProduct, 'user' => isUser(), 'categs' => $categs]);
                        } else {
                            $categories = [];
                            foreach ($_POST["categs"] as $categId) {
                                $categ = $categRepository->findOneBy(['categId' => $categId]);
                                array_push($categories, $categ);
                            }

                            $selectedProduct->setResume($_POST["resume"]);
                            $selectedProduct->setProductName($_POST["productName"]);
                            $selectedProduct->setImg($_POST["img"]);
                            if (isset($_POST["status"])) {
                                $selectedProduct->setStatus($_POST["status"]);
                            } else {
                                $selectedProduct->setStatus(0);
                            }
                            if (isset($_POST["ageRank"])) {
                                $selectedProduct->setAgeRank($_POST["ageRank"]);
                            } else {
                                $selectedProduct->setAgeRank(0);
                            }
                            $selectedProduct->setChapterNumber($_POST["chapterNumber"]);
                            $selectedProduct->setAverageRating(floatval($_POST["averageRating"]));
                            $selectedProduct->setNotAvailable(0);
                            $selectedProduct->setCategories($categories);

                            $em->persist($selectedProduct);
                            $em->flush();

                            header('Location: /admin/products');
                        }
                    } else {
                        //AJOUT
                        if (!$_POST) {
                            return new Response('admin/adminProduct.html.twig', ['lang' => $lang, 'user' => isUser(), 'categs' => $categs]);
                        } else {
                            $categories = [];
                            if(isset($_POST["categs"])){
                                foreach ($_POST["categs"] as $categId) {
                                    $categ = $categRepository->findOneBy(['categId' => $categId]);
                                    $categories[] = $categ;
                                }
                            }

                            $products = $productRepository->findBy(array(), array('productId' => 'DESC'));
                            $product = new Product();
                            $product->setProductId($products[0]->getProductId() + 1);
                            $product->setResume($_POST["resume"]);
                            $product->setProductName($_POST["productName"]);
                            $product->setImg($_POST["img"]);
                            if (isset($_POST["status"])) {
                                $product->setStatus($_POST["status"]);
                            } else {
                                $product->setStatus(0);
                            }
                            if (isset($_POST["ageRank"])) {
                                $product->setAgeRank($_POST["ageRank"]);
                            } else {
                                $product->setAgeRank(0);
                            }
                            $product->setChapterNumber($_POST["chapterNumber"]);
                            $product->setAverageRating(floatval($_POST["averageRating"]));
                            $product->setNotAvailable(0);
                            $product->setCategories($categories);

                            $em->persist($product);
                            $em->flush();

                            header('Location: /admin/products');
                        }
                    }
                } else {
                    echo($lang["ADMINUSERS"]["ERRORPWD"]);
                    die;
                }
            } else {
                echo($lang["ADMINUSERS"]["ERRORADMIN"]);
                die;
            }
        } else {
            header('Location: /');
        }
    }
}

