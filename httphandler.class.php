<?php
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
    if(strtolower($result) == 'index.php')
    {
      $session = new session;
      $session->setMessageSession('general', 'Invalid username and password combination.');
    }
    session_regenerate_id(true);
    header('Location:'.$result);
    session_write_close();
  }

  function requestpasswordreset()
  {
    $auth = new authenticate;
    $result = $auth->requestpasswordreset($this->postObject->username);
    if(isset($result->id))
    {
      $str = 'index.php?tprid='.$result->id.'&usr='.$result->username.'&pwd='.$result->password;
      header('Location:'.$str);
    }
    else
    {
      $session = new session;
      $session->setMessageSession('general','User unknown');
      session_regenerate_id(true);
      header('Location:index.php');
      session_write_close();
    }
  }

  function resetpassword()
  {
    $auth = new authenticate;
    $result = $auth->resetpassword($this->postObject->id, $this->postObject->username, $this->postObject->password);
    $session = new session;
    $session->setMessageSession('general','Password reset');
    session_regenerate_id(true);
    header('Location:index.php');
    session_write_close();
  }

  function selfregister()
  {
    $auth = new authenticate;
    $session = new session;
    $nextPage = 'index.php';

    /* Check if the passwords match */
    if($auth->passwordmatch($this->postObject->password1, $this->postObject->password2) == FALSE)
    {
      $session->setMessageSession('general','Passwords do not match');
    }
    else
    {
      $pwd = md5($this->postObject->password1);
      /* Check if the user already exists */
      if($auth->userAlreadyExists($this->postObject->username) == TRUE)
      {
        $session->setMessageSession('general','User already exists');
      }
      else
      {
        $nextPage = $auth->selfregister($this->postObject->username, $pwd);
        if(strtolower($nextPage) == 'index.php')
        {
          $session->setMessageSession('general','You are on the waiting list');
        }
      }
    }

    session_regenerate_id(true);
    header('Location:'.$nextPage);
    session_write_close();
  }

  function adminchanges()
  {
    $session = new session;
    $session->setUserSession($this->postObject->userid, $this->postObject->username);
    $config = new config;
    $config->updateini($this->postObject);
    session_regenerate_id(true);
    $url = 'private.php?sessionid='.$this->postObject->sessid;
    header('Location:'.$url);
    session_write_close();
  }

  // function singleuploadfile()
  // {
  //   $fu = new fileupload;
  //   $fu->webpath = $this->postObject->webpath;
  //   $fu->files = $this->fileObject;
  //   $fu->singleupload('1');
  // }

  // function multiuploadfile()
  // {
  //   $fu = new fileupload;
  //   $fu->webpath = $this->postObject->webpath;
  //   $fu->files = $this->fileObject;
  //   $fu->multiupload('1');
  // }
}
new httphandler($_GET, $_POST, $_FILES);
?>