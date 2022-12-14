<?php

namespace App\Controller;

use App\Entity\Categ;
use App\Repository\CategRepository;
use Framework\Doctrine\EntityManager;
use Framework\Response\Response;
use function App\getTextLangue;
use function App\isUser;
use function App\startSession;

class CategoriesPage{
    public function __invoke(){

        startSession();
        $em = EntityManager::getInstance();

        /** @var CategRepository$categRepository */
        $categRepository = $em->getRepository(Categ::class);
        $categories = $categRepository->findBy(array(), array('categName' => 'asc'));

        $args = ['lang' => getTextLangue($_SESSION['locale']), 'categories' => $categories, 'user' => isUser()];
        return new Response('categoriesPage.html.twig', $args);
    }
}
