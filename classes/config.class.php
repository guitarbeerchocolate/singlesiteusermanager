<?php
class config
{
	public $values = NULL;
	private $file = NULL;
	private $fileLocation = NULL;
	function __construct($file = NULL)
	{
		if($file != NULL)
		{
			$this->fileLocation = 'classes/'.$file.'.ini';
		}
		else
		{
			$this->fileLocation = 'classes/config.ini';
		}
		$tempArr = array();
		$this->file = file_get_contents($this->fileLocation);
		$tempArr = explode("\n", $this->file);
		$this->createAssoc($tempArr);
	}

	function createAssoc($ta)
	{
		foreach ($ta as $takey => $line)
		{
			$tempArr = explode(' = ',$line);
			$key = trim($tempArr[0]);
			if(isset($tempArr[1]))
			{
				$value = trim($tempArr[1]);
			}
			else
			{
				$value = NULL;
			}
			if(!isset($key) || !empty($key))
			{
				@$this->values->{$key} = $value;
			}
			unset($tempArr);
		}
	}

	function listValues()
	{
		return get_object_vars($this->values);
	}

	function updateini($po)
	{
		file_put_contents($this->fileLocation, '');
		foreach(get_object_vars($po) as $key => $value)
		{
			if(($key != 'method') && ($key != 'username') && ($key != 'sessid') && ($key != 'userid'))
			{
				$file = fopen($this->fileLocation, 'a') or exit("Unable to open file!");
				$this->updateFile($key, $value, $file);
				fclose($file);
			}
		}
	}

	function updateFile($k, $v, $f)
	{
		$newline = $k.' = '.$v.PHP_EOL;
		fwrite($f, $newline);
	}
}
?>
