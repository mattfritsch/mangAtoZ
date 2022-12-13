<?php

namespace App\Controller;


use App\Entity\Chapter;
use App\Entity\Product;
use App\Repository\ChaptersRepository;
use App\Repository\ProductRepository;
use Framework\Doctrine\EntityManager;
use Framework\Request\Request;
use Framework\Response\Response;
use function App\getTextLangue;
use function App\isUser;
use function App\startSession;

class ChaptersPage
{

    public function __invoke()
    {

        startSession();
        $recherche = null;
        if(isset($_POST['SubmitButton'])){ //check if form was submitted
            if($_POST['inputText']==null){
                $recherche = null;
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
        if (isset ($_GET["id"])) {
            $id = htmlspecialchars($_GET["id"]);
            $chapters = $chaptersRepository->findBy(['product' => $id]);
        }



        $args = ['lang' => getTextLangue($_SESSION['locale']), 'chapters' =>$chapters, 'recherche' =>$recherche, 'user' => isUser()];
        return new Response('chapterspage.html.twig', $args);
    }

}