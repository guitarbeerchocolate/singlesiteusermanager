<?php
if(isset($_GET['vid']) && isset($_GET['usr']) && isset($_GET['pwd']))
{

	$auth = new authenticate;
	if($auth->checkBeforeMove($_GET['usr'], $_GET['pwd']) == TRUE)
	{
		$auth->movefromwaitingtousers($_GET['vid'], $_GET['usr'], $_GET['pwd']);
		echo 'User registered and informed.';
	}
	else
	{
		echo 'Invalid credentials';
	}
}
else
{
	echo 'Not enough credentials';
}
?>