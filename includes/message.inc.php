<?php
if(isset($_GET['message']))
{
	echo urldecode($_GET['message']);
}
?>