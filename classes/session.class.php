<?php
@session_start();
class session
{
	public $userid;
	public $username;
	public $messagename;
	public $message;
	public $sessid;
	function __construct()
	{
		if(!isset($_SESSION)) 
	    { 
	        session_start(); 
	        $this->userid = NULL;
		$this->username = NULL;
		$this->messagename = NULL;
		$this->message = NULL;
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

	function setMessageSession($messagename, $message)
	{
		$this->messagename = $messagename;
		$this->message = $message;
		$_SESSION['MESSAGE_NAME'] = $this->messagename;   
		$_SESSION['MESSAGE_CONTENT'] = $this->message;
	}

	function getMessageSession()
	{
		if(isset($_SESSION['MESSAGE_NAME']))
		{
			$this->messagename = $_SESSION['MESSAGE_NAME'];
		}
		if(isset($_SESSION['MESSAGE_CONTENT']))
		{
			$this->message = $_SESSION['MESSAGE_CONTENT'];
		}
	}

	function destroyMessageSession()
	{
		$this->messagename = NULL;
		$this->message = NULL;
		unset($_SESSION['MESSAGE_ID']);
		unset($_SESSION['MESSAGE_CONTENT']);
	}

	function __destruct()
	{
		$this->sessid = NULL;
		$this->destroyUserSession();
		$this->destroyMessageSession();
	}
}
?>
