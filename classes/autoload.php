<?php
function __autoload($classname)
{
	$classString = 'classes/'.$classname.'.class.php';
	require_once $classString;
}
?>