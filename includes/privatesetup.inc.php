<?php
session_regenerate_id(true);
$session = new session;
$session->getUserSession();
if(!isset($session->username))
{
	$session->destroyUserSession();
	$headerString = 'Location:';
	$headerString .= 'index.php?message=';
	$headerString .= urlencode('Who are you?');
	header($headerString);
}
session_write_close();
?>