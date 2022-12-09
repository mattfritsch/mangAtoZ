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
      $_SESSION["locale"] = "en";

      $args = ['lang' => getTextLangue('en')];
      return new Response('home.html.twig', $args);
  }
}



