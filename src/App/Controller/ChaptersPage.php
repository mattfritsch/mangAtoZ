<?php

namespace App\Controller;


use App\Entity\Chapter;
use App\Repository\ChaptersRepository;
use Framework\Doctrine\EntityManager;
use Framework\Response\Response;
use function App\getTextLangue;
use function App\startSession;

class ChaptersPage
{

    public function __invoke()
    {
        startSession();

        $em = EntityManager::getInstance();

        /** @var ChaptersRepository$chaptersRepository */
        $chaptersRepository = $em->getRepository(Chapter::class);
        $chapters = $chaptersRepository->findBy(['product' => '4']);


        $args = ['lang' => getTextLangue('en'), 'chapters' =>$chapters];
        return new Response('chapterspage.html.twig', $args);
    }

}