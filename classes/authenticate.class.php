<?php
@session_start();
class authenticate
{
  public $id;
  private $username;
  private $password;
  private $db;
  private $session;
  private $config;

  function __construct()
  {
    $this->db = new database;
    $this->session = new session;
    $this->config = new config;
  }

  function login($u, $p)
  {
    $this->username = mysql_escape_string($u);
    $this->password = md5($p);
    $q = "SELECT * FROM `users` WHERE `username`='{$this->username}' AND `password`='{$this->password}'";
    $result = $this->db->singleRow($q);
    if(isset($result->id))
    {
      $this->id = $result->id;
      $this->username = $result->username;
      $this->session->setUserSession($this->id, $result->username);
      return 'private.php?sessionid='.$this->session->sessid;
    }
    else
    {
      $this->session->destroyUserSession();
      return 'index.php';
    }
  }

  function requestpasswordreset($u)
  {
    $this->username = mysql_escape_string($u);
    $q = "SELECT * FROM `users` WHERE `username`='{$this->username}'";
    $result = $this->db->singleRow($q);
    if(isset($result->id))
    {
      return $result;
    }
    else
    {
      return FALSE;
    }
  }

  function selfregister($u, $pwd)
  {
    $nextPage = 'index.php';
    if($this->config->values->AUTO_REGISTER == 'TRUE')
    {
      $id = $this->autoregister(mysql_escape_string($u), $pwd);
      $this->session->setUserSession($id, $u);
      $nextPage = 'private.php?sessionid='.$this->session->sessid;
    }
    else
    {
      $id = $this->registerwaiting(mysql_escape_string($u), $pwd);
      $email = new electronicmail;
      $email->to = $this->config->values->AUTHORISING_USER;
      $email->from = $this->config->values->MAILBOX_NAME;
      $email->subject = 'Verify user';
      $email->textmessage = 'Hello'.PHP_EOL;
      $email->textmessage .= 'A user taken part in the self-registration process.'.PHP_EOL;
      $email->textmessage .= 'Click or paste the link into your browsers address bar to verify access.'.PHP_EOL;
      $email->textmessage .= $this->config->values->WEB_LOCATION.'?vid='.$id.'&usr='.$u.'&pwd='.$pwd.PHP_EOL;
      $email->textmessage .= 'Admin.'.PHP_EOL;
      $email->sendemail();
    }
    return $nextPage;
  }

  function movefromwaitingtousers($id, $usr, $pwd)
  {
    $q = "INSERT INTO `users` VALUES (NULL, '{$usr}','{$pwd}')";
    $this->db->singleRow($q);
    $q = "DELETE FROM `waiting` WHERE `username`='{$usr}'";
    $this->db->singleRow($q);
    $email = new electronicmail;
    $email->to = $usr;
    $email->from = $this->config->values->MAILBOX_NAME;
    $email->subject = 'User verified';
    $email->textmessage = 'Hello'.PHP_EOL;
    $email->textmessage .= 'Your username has now been verified.'.PHP_EOL;
    $email->textmessage .= 'Please log on to :'.PHP_EOL;
    $email->textmessage .= $this->config->values->WEB_LOCATION.PHP_EOL;
    $email->textmessage .= 'Admin.'.PHP_EOL;
    $email->sendemail();
  }

  function testForReset($id, $username, $pwd)
  {
      $this->id = mysql_escape_string($id);
      $this->username = mysql_escape_string($username);
      $this->password = mysql_escape_string($pwd);
      $q = "SELECT * FROM `users` WHERE `id`='{$this->id}' AND `username`='{$this->username}' AND `password`='{$this->password}'";
      return $this->db->singleRow($q);
  }

  function resetpassword($id, $username, $pwd)
  {
    $this->id = mysql_escape_string($id);
    $this->username = mysql_escape_string($username);
    $this->password = md5($pwd);
    $q = "UPDATE `users` SET `password`='{$this->password}' WHERE `id`='{$this->id}' AND `username`='{$this->username}'";
    return $this->db->singleRow($q);
  }

  function autoregister($u, $pwd)
  {
    $q = "INSERT INTO `users` VALUES (NULL, '{$u}','{$pwd}')";
    $this->db->singleRow($q);
    return $this->db->lastAdded();
  }

  function registerwaiting($u, $pwd)
  {
    $q = "INSERT INTO `waiting` VALUES (NULL, '{$u}','{$pwd}')";
    $this->db->singleRow($q);
    return $this->db->lastAdded();
  }

  function passwordmatch($pwd1, $pwd2)
  {
    if($pwd1 != $pwd2)
    {
     return FALSE;
    }
    else
    {
      return TRUE;
    }
  }

  function userAlreadyExists($u)
  {
    $q = "SELECT * FROM `users` WHERE `username`='{$u}'";
    $result = $this->db->singleRow($q);
    if(isset($result->id))
    {
      return TRUE;
    }
    else
    {
      return FALSE;
    }
  }

  function logout()
  {
    $this->session->destroyUserSession();
  }

  function __destruct()
  {

  }
}
?>