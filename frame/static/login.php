<?php header($_SERVER["SERVER_PROTOCOL"]." 401 Unauthorized", true, 404); ?>

<div class="container" style="max-width: 400px">
	<form method="POST">

		<div class="form-group">
			<label>HPI-Login</label>
			<input class="form-control" name="login" type="text">
		</div>

		<div class="form-group">
			<label>Password</label>
			<input class="form-control" name="password" type="password">
		</div>

		<input type="submit" class="btn btn-default" value="Login">

	</form>
</div>

