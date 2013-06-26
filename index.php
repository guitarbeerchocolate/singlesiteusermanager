<?php
require_once 'classes/autoload.php';
include 'includes/message.inc.php';
if(isset($_GET['url']))
{
	$incStr = 'includes/'.$_GET['url'].'.inc.php';
	if(file_exists($incStr))
	{
		include $incStr;
	}
	else
	{
		echo 'Invalid call';
	}
}
else
{
	include 'includes/public.inc.php';
}
?>