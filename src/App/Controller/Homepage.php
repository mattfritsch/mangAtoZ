<?php

namespace App\Controller;


use App\Entity\Product;
use App\Repository\ProductRepository;
use Framework\Doctrine\EntityManager;
use Framework\Response\Response;
use function App\getTextLangue;
use function App\isUser;
use function App\startSession;

class Homepage
{
  public function __invoke()
  {
      ?>
      <script type="text/javascript">
          document.cookie="email=" + localStorage.getItem('email');
          document.cookie="password=" + localStorage.getItem('password');
      </script>
      <?php

      startSession();

      $em = EntityManager::getInstance();

      /** @var ProductRepository $productRepository */
      $productRepository = $em->getRepository(Product::class);
      $products = $productRepository->findBy(array(), array('averageRating' => 'asc'));

      $args = ['lang' => getTextLangue($_SESSION['locale']), 'products' => $products, 'user' => isUser()];
      return new Response('home.html.twig', $args);
  }
}



