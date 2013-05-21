<form class="form-signin form-horizontal" action="httphandler.class.php" method="POST">
	<fieldset>
		<legend>Enter your new password</legend>
		<input name="method" type="hidden" value="resetpassword" />
		<input name="id" type="hidden" value="<?php echo $result->id; ?>" />
		<input type="hidden" name="username" value="<?php echo $result->username; ?>" />
		<label for="password">Password</label>
		<input type="password" name="password" class="input-block-level" placeholder="New password" required />
		<button class="btn" type="submit">Register</button>
	</fieldset>
</form>