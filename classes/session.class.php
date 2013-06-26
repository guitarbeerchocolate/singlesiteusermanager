<?php
@session_start();
class session
{
	public $userid;
	public $username;
	public $sessid;
	function __construct()
	{
		if(!isset($_SESSION)) 
	    { 
	        session_start(); 
	        $this->userid = NULL;
			$this->username = NULL;
	    }
	$this->sessid = session_id();
	}

	function setUserSession($userid, $username)
	{
		$this->userid = $userid;
		$this->username = $username;
		$_SESSION['USER_ID'] = $this->userid;   
		$_SESSION['USER_NAME'] = $this->username;
	}

	function getUserSession()
	{
		if(isset($_SESSION['USER_ID']))
		{
			$this->userid = $_SESSION['USER_ID'];
		}
		if(isset($_SESSION['USER_NAME']))
		{
			$this->username = $_SESSION['USER_NAME'];
		}
	}

	function destroyUserSession()
	{
		$this->userid = NULL;
		$this->username = NULL;
		unset($_SESSION['USER_ID']);
		unset($_SESSION['USER_NAME']);
	}	

	function __destruct()
	{
		$this->sessid = NULL;
		$this->destroyUserSession();
	}
}
?>
