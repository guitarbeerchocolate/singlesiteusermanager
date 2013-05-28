<?php
class database
{
	private $config;
	private $connection;

	function __construct($file = NULL)
	{
		if($file != NULL)
		{
			$this->config = new config($file);
		}
		else
		{
			$this->config = new config;
		}		
		$this->connection = mysql_connect($this->config->values->DB_HOST, $this->config->values->DB_USERNAME, $this->config->values->DB_PASSWORD) or die('Connection to host failed.'.mysql_error());
		mysql_select_db($this->config->values->DB_NAME) or die('Database is not available.');
	}

	/* Example usage
	$results = $db->query("SELECT * FROM `users`");
	foreach($results as $row)
	{
		echo $row->id.'<br />';
	} */
	public function query($q)
	{
		$results = mysql_query($q, $this->connection);
		if(!$results)
		{
    		die('Invalid query: '.mysql_error());
		}
		else
		{
			$objArray = array();
			while($row = mysql_fetch_assoc($results))
			{
				array_push($objArray, (object) $row);
			}
			return $objArray;
		}
	}

	/* Example usage
	$result = $db->singleRow("SELECT * FROM `users` WHERE `id`='2'");
	echo $result->username;
	*/
	public function singleRow($q)
	{
		$result = mysql_query($q);
		$this->row = mysql_fetch_assoc($result);
		return (object) $this->row;
	}

	public function lastAdded()
	{
		return mysql_insert_id();
	}

	function __destruct()
	{
		$this->connection = NULL;
	}
}
?>