<?php
  // determine the page upfront and save the output.
  // Allows subpages to modify headers

  $domain = $_ENV["FRAME_DOMAIN"];
  $extraBodyClass = "";

  function embed($url) {
    global $extraBodyClass;
    $extraBodyClass = "navbar-no-spacing";
    echo "<iframe class=\"page-content\" src=\"$url\">";
  }

  ob_start();

  switch ($_SERVER["REQUEST_URI"]) {
  case "/":
    include("static/home.php");
    break;
  case "/login":
    include("static/login.php");
    break;
  case "/geocaching":
    include("static/geocaching.php");
    break;
  case "/120/":
  case "/120":
    embed("$domain/embed/120/");
    break;
  case "/180/":
  case "/180":
    embed("$domain/embed/180/");
    break;
  case "/etherpad/":
  case "/etherpad":
    include("static/etherpad.php");
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
  <body class="<?= $extraBodyClass ?>">
    <?php include("navbar.php"); ?>
    <?= $content ?>
  </body>
</html>

