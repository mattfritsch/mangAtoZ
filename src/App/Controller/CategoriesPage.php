<?php

namespace App\Controller;

use App\Entity\Categ;
use App\Repository\CategRepository;
use Framework\Doctrine\EntityManager;
use Framework\Response\Response;
use function App\getTextLangue;
use function App\startSession;

class CategoriesPage{
    public function __invoke(){

        startSession();
        $em = EntityManager::getInstance();

        /** @var CategRepository$categRepository */
        $categRepository = $em->getRepository(Categ::class);
        $categories = $categRepository->findAll();

        $args = ['lang' => getTextLangue($_SESSION['locale']), 'categories' => $categories];
        return new Response('categoriesPage.html.twig', $args);
    }
}
