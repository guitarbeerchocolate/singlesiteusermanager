<html>
	<head>
		<title>Single site user manager</title>
		<style>
		body
		{
			font:100%/1.618 sans-serif;
			color:#666666;
		}
    label, button
    {
      display:block;
      clear:both;
    }
		</style>
	</head>
	<body>
	<!-- Copy from here to 'End' and paste into your website -->
	<?php
      include_once 'classes/autoload.php';
      $messagesession = new session;
      $messagesession->getMessageSession();
      /* testing for password reset */
      if(isset($_GET['tprid']) && isset($_GET['usr']) && isset($_GET['pwd']))
      {
        $auth = new authenticate;
        $result = $auth->testForReset($_GET['tprid'], $_GET['usr'], $_GET['pwd']);
        if(isset($result->id))
        {
          include 'includes/resetpasswordform.inc.php';
        }
        else
        {
          $messagesession->setMessageSession('requestpasswordreset', 'Invalid credentials');
        }
      }
      elseif(isset($_GET['vid']) && isset($_GET['usr']) && isset($_GET['pwd']))
      {
        $auth = new authenticate;
        $auth->movefromwaitingtousers($_GET['vid'], $_GET['usr'], $_GET['pwd']);
        $messagesession->setMessageSession('general', 'User verified');
        session_regenerate_id(true);
          header('Location:index.php');
          session_write_close();
      }
      else
      {
        include 'includes/generalmessage.inc.php';
        include 'includes/loginform.inc.php';
        include 'includes/requestpasswordresetform.inc.php';
        include 'includes/selfregisterform.inc.php';

        $messagesession->destroyMessageSession();
      }
      ?>
	<!-- End -->
	</body>
</html>