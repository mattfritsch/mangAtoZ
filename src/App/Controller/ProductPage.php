<?php

namespace App\Controller;


use App\Repository\CategRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Framework\Doctrine\EntityManager;
use Framework\Response\Response;
use App\Entity\Product;
use App\Entity\Categ;
use function App\age;
use function App\getTextLangue;
use function App\isUser;
use function App\startSession;

class ProductPage
{
    public function __invoke()
    {
        startSession();
        $lang = getTextLangue($_SESSION["locale"]);

        if (isset($_SESSION['user']))
            $age = age();
        else
            $age = 0;

        $search = null;
        $search = $this->getValue('submitButton', 'searchBar', $search);


        $order = null;
        $order = $this->getValue('validateButton', 'order', $order);

        $status = null;
        $status = $this->getValue('validateButton', 'radioStatus', $status);

        $censure = null;
        $censure = $this->getValue('validateButton', 'radioCensure', $censure);


        $em = EntityManager::getInstance();

        /** @var ProductRepository $productRepository */
        $productRepository = $em->getRepository(Product::class);
        $products = $productRepository->findBy(['ageRank' => '0', 'notAvailable' => '0']);

        /** @var CategRepository $categRepository */
        $categRepository = $em->getRepository(Categ::class);
        $categs = $categRepository->findBy(array(), array('categName' => 'asc'));

        $filtered = false;

        if (isset($_POST['submitButton']) || isset($_POST['validateButton'])) {
            if(isset($_POST['categs'])){
                $categ = $_POST['categs'];
            }
            else{
                $categ = '';
            }
            $filtered = true;
            $productsFiltered = $productRepository->getFilteredProducts(['search' => $search, 'order' => $order,
                'status' => $status, 'censure' => $censure, 'categories' => $categ]);
        }


            if (isset($_POST["method"])) {
                if ($_POST["method"] === "getProducts"){
                    //categs
                    if($_POST["categ0"] != ""){
                        for($i = 0; $i < intval($_POST["nb_categs"]); $i++){
                            $categs[] = $_POST["categ" . $i];
                        }
                    } else {
                        $categs = '';
                    }

                    $search = null;
                    if($_POST["search"] !== "")
                        $search = $_POST["search"];


                    $order = null;
                    if($_POST["order"] !== "neutral")
                        $order = $_POST["order"];

                    $status = null;
                    if(isset($_POST["status"]))
                        $status = $_POST["status"];

                    $censure = null;
                    if(isset($_POST["censure"]))
                        $censure = $_POST["censure"];


                    $query = $productRepository->getFilteredProducts(['search' => $search, 'order' => $order,
                        'status' => $status, 'censure' => $censure, 'categories' => $categs])
                        ->setFirstResult($_POST["min"])
                        ->setMaxResults($_POST["max"]);

                    $products = $query->getResult();

                    $paginator = new Paginator($query, $fetchJoinCollection = true);

                    $data = [];

                    foreach ($products as $index => $product){
                        $data["name" . $index] = $product->getProductName();
                        $data["img" . $index] = $product->getImg();
                        $data["chapterNumber" . $index] = $product->getChapterNumber();
                        $data["resume" . $index] = $product->getResume();
                        $data["productId" . $index] = $product->getProductId();
                    }

                    $data["resume"] = $lang["PRODUCT"]["DESCRIPTION"];
                    $data["chapter"] = $lang["PRODUCT"]["CHAPTER"];
                    $data["chapters"] = $lang["PRODUCT"]["CHAPTERS"];
                    $data["go_chapters"] = $lang["PRODUCT"]["GO_CHAPTERS"];
                    $data["go_chapter"] = $lang["PRODUCT"]["GO_CHAPTER"];
                    $data["nbResults"] = strval(count($paginator));

                    echo json_encode($data);
                }
            } else {
                if(isset($_POST['categs'])){
                    $selectedCategs = $_POST['categs'];
                }
                else{
                    $selectedCategs = '';
                }

                $nothing = null;

                if($filtered === true){
                    if (sizeof($productsFiltered->getResult()) === 0){
                        $nothing = true;
                    }
                } else {
                    if (sizeof($products) === 0){
                        $nothing = true;
                    }
                }

                $args = ['lang' => $lang, 'products' => $products, 'categs' => $categs,
                    'search' => $search, 'age' => $age, 'order' => $order, 'radioStatus' => $status, 'censureAdd' => $censure,
                    'user' => isUser(), 'selectedCategs' => $selectedCategs, 'nothing' => $nothing];
                return new Response('productPage.html.twig', $args);
            }
    }

    public function getValue(string $buttonName, string $fieldName, ?string $value): ?string
    {
        if (isset($_POST[$buttonName])) {
            if (isset($_POST[$fieldName])) {
                $value = $_POST[$fieldName];
            } else {
                $value = null;
            }
        }
        return $value;
    }

}
