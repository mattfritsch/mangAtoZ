<?php

namespace App\Controller;


use Framework\Response\Response;
use function App\getTextLangue;
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
      if($_SESSION['locale'] = ''){
          $_SESSION['locale'] = 'fr';
      }

      $args = ['lang' => getTextLangue($_SESSION['locale'])];
      return new Response('home.html.twig', $args);
  }
}



