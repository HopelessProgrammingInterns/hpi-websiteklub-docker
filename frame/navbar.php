<?php

$domain = $_ENV["FRAME_DOMAIN"];

function iterateOverEnvVars($envVarBase, $cb) {
	global $domain;

	$i = 0;
	while (isset($_ENV["$envVarBase$i"])) {
		$item = $_ENV["$envVarBase$i"];
		list($label, $link) = explode("|", $item);

		$cb($label, $link[0] == '/' ? $domain.$link : $link);

		$i++;
	}
}

// output in JSON
if (isset($_GET["type"]) && $_GET["type"] == "json") {
	header('Content-Type: application/json');

	echo "{\"links\":[";

	// output links
	$first = true;
	iterateOverEnvVars("LINK", function($label, $link) {
		global $first;
		if (!$first) echo ",";
		$first = false;
		echo "{\"label\":\"$label\",\"link\":\"$link\"}";
	});
	echo "],\"clubs\":[";

	// output clubs
	$first = true;
	iterateOverEnvVars("CLUB", function($label, $link) {
		global $first;
		if (!$first) echo ",";
		$first = false;
		echo "{\"label\":\"$label\",\"link\":\"$link\"}";
	});
	echo "]}";
	exit;
}
?>

<link rel="stylesheet" href="<?= $domain ?>/css/bootstrap.min.css">
<link rel="stylesheet" href="<?= $domain ?>/css/bootstrap-hpi-theme.css">
<nav class="navbar navbar-default">
  <div class="container-fluid">
	  <div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#hpi-navbar-collapse" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="#"><img src="<?= $domain ?>/logo.png"></a>
	  </div>

	  <div class="collapse navbar-collapse" id="hpi-navbar-collapse">
			<ul class="nav navbar-nav">
				<?php
						iterateOverEnvVars("LINK", function($label, $link) {
							echo '<li><a href="'.$link.'">'.$label.'</a></li>';
						});
				?>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Klubs <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<?php
								iterateOverEnvVars("CLUB", function($label, $link) {
									echo '<li><a href="'.$link.'">'.$label.'</a></li>';
								});
						?>
					</ul>
				</li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li><a href="#">Logout</a></li>
			</ul>
	  </div>
	</div><!-- /.container-fluid -->
</nav>
<script src="<?= $domain ?>/js/jquery.min.js"></script>
<script src="<?= $domain ?>/js/bootstrap.min.js"></script>
