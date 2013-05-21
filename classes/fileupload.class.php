<?php
class fileupload
{
	private $config = NULL;
	public $upload_file_location = NULL;
	public $webpath = NULL;
	public $files = NULL;
	function __construct()
	{
		$this->config = new config;
		if($this->config->values->UPLOAD_FILE_LOCATION)
		{
			$this->upload_file_location = $this->config->values->UPLOAD_FILE_LOCATION;
		}
	}

	function singleupload($subDir = NULL)
	{
		foreach ($this->files as $file)
		{
			$filester = (object) $file;
			$target = $this->appendSlash($this->upload_file_location).basename($filester->name);
			if($subDir)
			{
				mkdir($this->upload_file_location.'/'.$subDir, 0777);
				$target = $this->appendSlash($this->upload_file_location).$subDir.'/'.basename($filester->name);
			}			
			move_uploaded_file($filester->tmp_name, $target);			
		}
	}

	function multiupload($subDir = NULL)
	{
		foreach ($this->files as $file)
		{
		 	$filester = (object) $file;
		 	for($i = 0; $i < count($filester->name); $i++) 
		 	{
		 		$target = $this->appendSlash($this->upload_file_location).basename($filester->name[$i]);
		 		if($subDir)
				{
					mkdir($this->upload_file_location.'/'.$subDir, 0777);
					$target = $this->appendSlash($this->upload_file_location).$subDir.'/'.basename($filester->name[$i]);
				}
		 		move_uploaded_file($filester->tmp_name[$i], $target);			
		 	}		
		}		
	}

	function appendSlash($s)
	{
		if(substr($s, -1) != '/')
		{
			$s .= '/';
		}
		return $s;
	}

	function __destruct()
	{
	
	}
}
?>