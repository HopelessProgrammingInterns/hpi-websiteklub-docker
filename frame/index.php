<?php
  // determine the page upfront and save the output.
  // Allows subpages to modify headers

  function embed($url) {
    echo "<div class=\"container\"><iframe class=\"page-content\" src=\"$url\"></div>";
  }

  ob_start();

  switch ($_SERVER["REQUEST_URI"]) {
  case "/":
    include("static/home.php");
    break;
  case "/geocaching":
    include("static/geocaching.php");
    break;
  default:
    include("static/404.php");
    break;
  }

  $content = ob_get_contents();
  ob_end_clean();
?>

<!doctype html>

<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>HPI Student</title>
  </head>
  <body>
    <?php include("navbar.php"); ?>
    <?= $content ?>
  </body>
</html>

