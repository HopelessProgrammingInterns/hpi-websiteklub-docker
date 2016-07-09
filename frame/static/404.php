<?php header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404); ?>

<div class="container">
	<h1 style="font-size: 7em">404. <small>Not Found</small></h1>
	<h5>So sad :( (<?= $_SERVER["REQUEST_URI"] ?>)</h5>
</div>
