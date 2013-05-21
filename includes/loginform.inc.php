<form id="loginform" class="form-signin form-horizontal" action="httphandler.class.php" method="POST">
	<fieldset>
		<legend>Please sign in</legend>
		<input name="method" type="hidden" value="login" />
		<label for="username">Email</label>
		<input type="email" name="username" class="input-block-level" placeholder="Email address" required />
		<label for="password">Password</label>
		<input type="password" name="password" class="input-block-level" placeholder="Password" required />
		<button class="btn" type="submit">Sign in</button>
	</fieldset>
</form>