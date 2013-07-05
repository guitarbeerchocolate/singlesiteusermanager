<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
require_once 'classes/autoload.php';
class httphandler
{
  private $getObject;
  private $postObject;
  private $fileObject;
  private $webpath;
  private $config;

  function __construct($get = NULL, $post = NULL, $file = NULL)
  {
    $this->config = new config;
    if(!empty($get))
    {
     $this->getObject = (object) $get;
     $this->checkForGetWebPath();
     $this->checkForGetMethod();
    }
    if(!empty($post))
    {
      $this->postObject = (object) $post;
      $this->checkForPostWebPath();
      if(!empty($file))
      {
        $this->fileObject = $file;
      }
      $this->checkForPostMethod();
    }
  }

  private function checkForGetMethod()
  {
    if($this->getObject->method && (method_exists($this, $this->getObject->method)))
    {
      $evalStr = '$this->'.$this->getObject->method.'();';
      eval($evalStr);
    }
    else
    {
      $oStr = 'Invalid method supplied';
      if($this->webpath)
      {
        header('Location:'.$this->webpath.'?message='.urlencode($oStr));
      }
      else
      {
        echo $oStr;
      }
    }
  }

  private function checkForPostMethod()
  {
    if($this->postObject->method && (method_exists($this, $this->postObject->method)))
    {
      $evalStr = '$this->'.$this->postObject->method.'();';
      eval($evalStr);
    }
    else
    {
      $oStr = 'Invalid method supplied';
      if($this->webpath)
      {
        header('Location:'.$this->webpath.'?message='.urlencode($oStr));
      }
      else
      {
        echo $oStr;
      }
    }
  }

  private function checkForGetWebPath()
  {
    if(isset($this->getObject->webpath))
    {
      $this->webpath = urldecode($this->getObject->webpath);
    }
  }

  private function checkForPostWebPath()
  {
    if(isset($this->postObject->webpath))
    {
      $this->webpath = urldecode($this->postObject->webpath);
    }
  }

  /* User functions here */
  function login()
  {
    $auth = new authenticate;
    $result = $auth->login($this->postObject->username, $this->postObject->password);
    $headerString = 'Location:'.$result;
    if($result == NULL)
    {
      $headerString .= 'index.php?message=';
      $headerString .= urlencode('Who are you?');
    }
    else
    {
      $headerString .= '?message=';
      $headerString .= urlencode('Welcome');
    }
    session_regenerate_id(true);
    header($headerString);
    session_write_close();
  }

  function requestpasswordreset()
  {
    $auth = new authenticate;
    $result = $auth->requestpasswordreset($this->postObject->username);
    $headerString = 'Location:';
    if(isset($result->id))
    {
      $email = new electronicmail;
      $email->to = $result->username;
      $email->from = $this->config->values->MAILBOX_NAME;
      $email->subject = 'Password reset';
      $email->textmessage = 'Please use the following link to reset your password'.PHP_EOL;
      $email->textmessage .= $this->config->values->WEB_LOCATION;
      $email->textmessage .= 'resetpasswordform?tprid='.$result->id.'&usr='.$result->username.'&pwd='.$result->password;
      $response = $email->sendemail();
      $headerString .= 'index.php?message=';
      $headerString .= urlencode('A message has been sent your your email account.');
    }
    else
    {
      $headerString .= 'index.php?message=';
      $headerString .= urlencode('User unknown');
    }
    header($headerString);
  }

  function resetpassword()
  {
    $auth = new authenticate;
    $result = $auth->resetpassword($this->postObject->id, $this->postObject->username, $this->postObject->password);
    $headerString = 'Location:';
    $headerString .= 'index.php?message=';
    $headerString .= urlencode('Password reset');
    header($headerString);
  }

  function selfregister()
  {
    $auth = new authenticate;
    $nextPage = 'index.php';
    $headerString = 'Location:';

    /* Check if the passwords match */
    if($auth->passwordmatch($this->postObject->password1, $this->postObject->password2) == FALSE)
    {
      $headerString .= 'index.php?message=';
      $headerString .= urlencode('Passwords do not match');
    }
    else
    {
      /* Check if the user already exists */
      if($auth->userAlreadyExists($this->postObject->username) == TRUE)
      {
        $headerString .= 'index.php?message=';
        $headerString .= urlencode('User already exists');
      }
      else
      {
        $nextPage = $auth->selfregister($this->postObject->username, $this->postObject->password1);
        if(strtolower($nextPage) == 'index.php')
        {
          $headerString .= 'index.php?message=';
          $headerString .= urlencode('You are on the waiting list');
        }
        else
        {
          $headerString .= $nextPage;
        }
      }
    }
    session_regenerate_id(true);
    header($headerString);
    session_write_close();
  }

  function adminchanges()
  {
    $session = new session;
    $session->setUserSession($this->postObject->userid, $this->postObject->username);
    $this->config->updateini($this->postObject);
    session_regenerate_id(true);
    $url = 'private.php?sessionid='.$this->postObject->sessid;
    header('Location:'.$url);
    session_write_close();
  }
}
new httphandler($_GET, $_POST, $_FILES);
?>