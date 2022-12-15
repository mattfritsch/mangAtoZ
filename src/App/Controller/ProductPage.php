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

        if (isset($_POST['submitButton']) || isset($_POST['validateButton'])) {
            if(isset($_POST['categs'])){
                $categ = $_POST['categs'];
            }
            else{
                $categ = '';
            }
            $_SESSION['category'] = $categ;
            $products = $productRepository->getFilteredProducts(['search' => $search, 'order' => $order,
                'status' => $status, 'censure' => $censure, 'categories' => $categ]);
        }


            if (isset($_POST["method"])) {
                if ($_POST["method"] === "getProducts"){
//                    $query = $productRepository->getFilteredProducts(['search' => $search, 'order' => $order,
//                        'status' => $status, 'censure' => $censure])
                    $dql = "SELECT p FROM App\Entity\Product p";
                    $query = $em->createQuery($dql)
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

                $args = ['lang' => $lang, 'products' => $products, 'categs' => $categs,
                    'search' => $search, 'age' => $age, 'order' => $order, 'radioStatus' => $status, 'censureAdd' => $censure,
                    'user' => isUser()];
                return new Response('productPage.html.twig', $args);

        //if(isset($_SESSION['category'])){
        //    $categories = $_SESSION['category'];
          //  foreach ($categories as $category){
            //    $selectedCateg = $categRepository->getSelectedCategories($category);
              //  $selectedCategs[] = $selectedCateg;
                //$_SESSION['categories'] = $selectedCategs;
            //}
          //else{
            //  $selectedCategs = null;
              //$_SESSION['categories'] = $selectedCategs;
          //}
        }

        if (isset($_SESSION['user']))
            $age = age();
        else
            $age = 0;

        $args = ['lang' => getTextLangue($_SESSION['locale']), 'products' => $products, 'categs' => $categs,
            'search' => $search, 'age' => $age, 'order' => $order, 'radioStatus' => $status, 'censureAdd' => $censure,
            'user' => isUser(), 'selectedCategs' => $_SESSION['categories']];
        return new Response('productPage.html.twig', $args);


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
