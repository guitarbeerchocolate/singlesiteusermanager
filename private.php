<?php
@session_start();
include_once 'classes/autoload.php';
include_once 'includes/privatesetup.inc.php';
?>
<html>
	<head>
		<title>Private : Single site user manager</title>
		<style>
		body
		{
			font:100%/1.618 sans-serif;
			color:#666666;
		}
		</style>
	</head>
	<body>
	<?php
	echo 'session name = '.$session->username.'<br />';
	echo 'session is = '.$session->sessid.'<br />';
	?>
	<?php
	/* Admin panel for managing the site */
	$config = new config;
	if($config->values->AUTHORISING_USER == $session->username)
	{
		include 'includes/adminoptions.inc.php';
	}
	?>
	</body>
</html>