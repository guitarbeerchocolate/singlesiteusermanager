<form action="httphandler.class.php" method="POST">
	<fieldset>
		<legend>Reset password</legend>
		<input name="method" type="hidden" value="requestpasswordreset" />
		<label for="username">Email</label>
		<input name="username" type="email" class="input-block-level" placeholder="Email address" required />
		<button class="btn" type="submit">Submit</button>
	</fieldset>
</form>