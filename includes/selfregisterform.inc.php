<form id="selfregisterform" class="form-signin form-horizontal" action="httphandler.class.php" method="POST">
    <fieldset>
    <legend>Self registration</legend>
        <input name="method" type="hidden" value="selfregister" />
    	<label for="username">Email</label>
    	<input type="email" name="username" class="input-block-level" placeholder="Email address" required />
    	<label for="password1">Password</label>
    	<input type="password" name="password1" class="input-block-level" placeholder="Password" required />
    	<label for="password2">Re-enter password</label>
    	<input type="password" name="password2" class="input-block-level" placeholder="Re-enter password" required />
    	<button class="btn" type="submit">Register</button>
    </fieldset>
</form>