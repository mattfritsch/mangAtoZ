<?php

namespace App\Controller;


use App\Repository\CategRepository;
use App\Repository\ProductRepository;
use Framework\Doctrine\EntityManager;
use Framework\Response\Response;
use App\Entity\Product;
use App\Entity\Categ;
use function App\age;
use function App\getTextLangue;
use function App\isUser;
use function App\startSession;

class ProductPage{
    public function __invoke()
    {
        startSession();
        if(isset($_POST['categories'])){
            $categories = json_decode($_POST['categories']);
            $_SESSION['categories'] = $categories;
            echo json_encode($categories);
        }
        else{

            $search = null;
            $search = $this->getValue('submitButton', 'searchBar', $search);

            $order = null;
            $order = $this->getValue('validateButton','order', $order);

            $status = null;
            $status = $this->getValue('validateButton','radioStatus', $status);

            $censure = null;
            $censure = $this->getValue('validateButton','radioCensure', $censure);


            $em = EntityManager::getInstance();

            /** @var ProductRepository $productRepository */
            $productRepository = $em->getRepository(Product::class);
            $products = $productRepository->findBy(['ageRank' => '1']);

            if (isset($_POST['submitButton']) || isset($_POST['validateButton'])) {
                $products = $productRepository->getFilteredProducts(['search'=> $search, 'order' => $order,
                    'status' => $status, 'censure' => $censure, 'categories' => $_SESSION['categories']]);
                $_SESSION['categories'] = '';
            }

            /** @var CategRepository$categRepository */
            $categRepository = $em->getRepository(Categ::class);
            $categs = $categRepository->findBy(array(), array('categName' => 'asc'));

            if (isset($_SESSION['user']))
                $age = age();
            else
                $age = 0;

            $args = ['lang' => getTextLangue($_SESSION['locale']), 'products' => $products, 'categs' => $categs,
                'search' => $search, 'age' => $age, 'order' => $order, 'radioStatus' => $status, 'censureAdd' => $censure,
                'user' => isUser()];
            return new Response('productPage.html.twig', $args);
        }

    }

    public function getValue(string $buttonName, string $fieldName, ?string $value) : ?string{
        if(isset($_POST[$buttonName])){
            if($_POST[$fieldName] != null){
                $value = $_POST[$fieldName];
            }
            else{
                $value = null;
            }
        }
        return $value;
    }

}
