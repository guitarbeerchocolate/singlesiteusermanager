<?php
@session_start();
/*
	Title : electronicmail
	Parameters : none
	Description : Creates and send e-mails based on parameters. Emails coan also contain HTML and file attachments.
*/
class electronicmail
{
	public $to = NULL;
	public $from = NULL;
	public $subject = NULL;
	public $textmessage = NULL;
	public $htmlmessage = NULL;
	public $attachment = NULL;
	private $headers = NULL;

	function __construct()
	{

	}

	function sendemail()
	{
		$response = NULL;
		if(!$this->to)
		{
			$response .= 'No one to send to'.PHP_EOL;
		}
		else
		{
			if(($this->textmessage) || !($this->htmlmessage))
			{
				$response .= $this->sendSimple();
			}
			else
			{
				$response .= $this->sendHTML();
			}
		}
		return $response;
	}

	private function sendSimple()
	{
		$this->headers = 'From: '.$this->from."\r\n";
		if($this->attachment)
		{
			$this->addAttachment();
		}
		else
		{
			mail($this->to,$this->subject,$this->textmessage,$this->headers);
		}
		return '<br />Sent simple';
	}

	private function sendHTML()
	{
		if($this->attachment)
		{
			$this->addAttachment();
		}
		else
		{
			mail($this->to,$this->subject,$this->htmlmessage,$this->headers);
		}
		return 'sent html';
	}

	private function addAttachment()
	{
		if($this->textmessage)
		{
			$htmlbody = $this->textmessage;
			$textmessage = $this->textmessage;
		}
		if($this->htmlmessage)
		{
			$htmlbody = $this->htmlmessage;
			$textmessage = strip_tags($this->htmlmessage);
		}

		$this->headers = "From: ".$this->from."\r\nReply-To: ".$this->from;
		$random_hash = md5(date('r', time()));
		$this->headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\"";
		$message = "--PHP-mixed-$random_hash\r\n"."Content-Type: multipart/alternative; boundary=\"PHP-alt-$random_hash\"\r\n\r\n";
		$message .= "--PHP-alt-$random_hash\r\n"."Content-Type: text/plain; charset=\"iso-8859-1\"\r\n"."Content-Transfer-Encoding: 7bit\r\n\r\n";
		$message .= strip_tags($textmessage);
		$message .= "\r\n\r\n--PHP-alt-$random_hash\r\n"."Content-Type: text/html; charset=\"iso-8859-1\"\r\n"."Content-Transfer-Encoding: 7bit\r\n\r\n";
		$message .= $htmlbody;
		$message .="\r\n\r\n--PHP-alt-$random_hash--\r\n\r\n";
		$message .= "--PHP-mixed-$random_hash\r\n"."Content-Type: ".mime_content_type($this->attachment)."; name=\"".$this->attachment."\"\r\n"."Content-Transfer-Encoding: base64\r\n"."Content-Disposition: attachment\r\n\r\n";
		$message .= chunk_split(base64_encode(file_get_contents($this->attachment)));
		$message .= "/r/n--PHP-mixed-$random_hash--";
		mail($this->to, $this->subject , $message, $this->headers);
	}

	function __destruct()
	{

	}
}
?>