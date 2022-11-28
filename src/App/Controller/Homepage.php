<?php

namespace App\Controller;


use Framework\Response\Response;
use function App\getTextLangue;

class Homepage
{
  public function __invoke()
  {
//        $em = EntityManager::getInstance();

        /*$user = new User();
        $user
            ->setFirstName('Boris')
            ->setLastName('CERATI')
            ->setEmail('cerati.boris@gmail.com');

        $em->persist($user);
        $em->flush();*/

//        /** @var UserRepository$userRepository */
//      $userRepository = $em->getRepository(User::class);
//      // $users = $userRepository->findAll();
//      $user = $userRepository->findOneByEmail('cerati.boris@gmail.com');

//      function getTextLangue(string $language, string $file){
//          $fr = require_once dirname(dirname(dirname(__DIR__))). '/locale/fr.php';
//          $en = require_once dirname(dirname(dirname(__DIR__))). '/locale/en.php';
//
//          if ($language === 'fr' ){
//              $l = $fr;
//          }
//          else{
//              $l = $en;
//          }
//
//          return $l[$file];
//      }



      $args = ['lang' => getTextLangue('en', 'HOME')];
      return new Response('home.html.twig', $args);
  }
}



