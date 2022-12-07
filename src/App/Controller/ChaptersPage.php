<?php

namespace App\Controller;


use App\Entity\Chapter;
use App\Repository\ChaptersRepository;
use Framework\Doctrine\EntityManager;
use Framework\Request\Request;
use Framework\Response\Response;
use function App\getTextLangue;

class ChaptersPage
{

    public function __invoke()
    {

        var_dump($_POST);
        $recherche = null;
        if(isset($_POST['SubmitButton'])){ //check if form was submitted
            if($_POST['inputText']==null){
                $recherche =null;
                echo $recherche;
            }
            else {
                $input = $_POST['inputText']; //get input text
                $recherche = $input;
                echo $recherche;

            }
        }

        $em = EntityManager::getInstance();

        /** @var ChaptersRepository$chaptersRepository */
        $chaptersRepository = $em->getRepository(Chapter::class);
        $chapters = $chaptersRepository->findBy(['product' => '12']);


        $args = ['lang' => getTextLangue('en'), 'chapters' =>$chapters, 'recherche' =>$recherche];
        return new Response('chapterspage.html.twig', $args);
    }

}