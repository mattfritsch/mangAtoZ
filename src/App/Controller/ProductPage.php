<?php

namespace App\Controller;


use App\Repository\CategRepository;
use App\Repository\ProductRepository;
use Framework\Doctrine\EntityManager;
use Framework\Response\Response;
use App\Entity\Product;
use App\Entity\Categ;
use function App\getTextLangue;

class ProductPage{
    public function __invoke()
    {
        $search = null;
        $search = $this->getValue('submitButton', 'searchBar', $search);

        $categories = null;
        $categories = $this->getValue('validateButton', 'categories', $categories);

        $order = null;
        $order = $this->getValue('validateButton','order', $order);

        $status = null;
        $status = $this->getValue('validateButton','radioStatus', $status);

        $censure = null;
        $censure = $this->getValue('validateButton','radioStatus', $censure);


        $em = EntityManager::getInstance();

        /** @var ProductRepository $productRepository */
        $productRepository = $em->getRepository(Product::class);
        $products = $productRepository->findAll();

        if (isset($_POST['submitButton']) || isset($_POST['validateButton'])) {
            $products = $productRepository->getFilteredProducts(['search'=> $search, 'order' => $order, 'status' => $status, 'censure' => $censure]);
        }

        /** @var CategRepository$categRepository */
        $categRepository = $em->getRepository(Categ::class);
        $categs = $categRepository->findAll();

        $args = ['lang' => getTextLangue('en'), 'products' => $products, 'categs' => $categs,
            'search' => $search];
        return new Response('productPage.html.twig', $args);
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
