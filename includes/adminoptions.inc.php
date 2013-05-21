<aside class="span3">
	<header>
		<h2>Admin values</h2>
	</header><br />
	<form class="form-signin form-horizontal" action="httphandler.class.php" method="POST">
		<fieldset>
			<input name="method" type="hidden" value="adminchanges" />
			<input name="username" type="hidden" value="<?php echo $session->username; ?>" />
			<input name="userid" type="hidden" value="<?php echo $session->userid; ?>" />
			<input name="sessid" type="hidden" value="<?php echo $session->sessid; ?>" />
			<?php
			foreach($config->listvalues() as $key => $value)
			{
				echo '<label for="'.$key.'">'.$key.'</label>';
				if($key == 'AUTO_REGISTER')
				{
					echo '<select name="'.$key.'">';
					echo '<option value="TRUE"';
					if($value == 'TRUE') echo 'SELECTED';
					echo '>TRUE</option>';
					echo '<option value="FALSE"';
					if($value == 'FALSE') echo 'SELECTED';
					echo '>FALSE</option>';
					echo '</select>';
				}
				else
				{
					if(strstr($key, '='))
					{
						$parsedKey = trim(str_replace('=','',$key));
					}
					else
					{
						$parsedKey = $key;
					}
					echo '<input type="';
					if(($parsedKey == 'AUTHORISING_USER') || ($parsedKey == 'MAILBOX_NAME'))
					{
						echo 'email';
					}
					elseif($parsedKey == 'WEB_LOCATION')
					{
						echo 'url';
					}
					else
					{
						echo 'text';
					}
					echo '" name="'.$parsedKey.'" class="input-block-level" ';
					if(isset($value) || !empty($value))	echo 'value="'.$value.'"';
					if($parsedKey != 'DB_PASSWORD') echo ' required';
					echo ' />';
				}
			}
			?>
			<button class="btn" type="submit">Update</button>
		</fieldset>
	</form>
</aside>