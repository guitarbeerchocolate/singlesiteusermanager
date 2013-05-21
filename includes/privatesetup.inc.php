<?php
$session = new session;
$session->getUserSession();
if(!isset($session->username))
{
	$session->destroyUserSession();
	header('Location:index.php');
}
if(isset($_GET['sessionid']))
{
	$session->sessid = $_GET['sessionid'];
}
else
{
	header('Location:index.php');
}
?>