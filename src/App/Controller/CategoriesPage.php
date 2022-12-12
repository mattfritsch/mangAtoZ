<?php

namespace App\Controller;

use App\Entity\Categ;
use App\Repository\CategRepository;
use Framework\Doctrine\EntityManager;
use Framework\Response\Response;
use function App\getTextLangue;

class CategoriesPage{
    public function __invoke(){

        $em = EntityManager::getInstance();

        /** @var CategRepository$categRepository */
        $categRepository = $em->getRepository(Categ::class);
        $categories = $categRepository->findAll();

        $args = ['lang' => getTextLangue('en'), 'categories' => $categories];
        return new Response('categoriesPage.html.twig', $args);
    }
}
