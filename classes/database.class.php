<?php
class database
{
	private $config;
	private $connection;
	function __construct()
	{
		$this->config = new config;
		$this->connection = new mysqli($this->config->values->DB_HOST, $this->config->values->DB_USERNAME, $this->config->values->DB_PASSWORD, $this->config->values->DB_NAME);
	}

	function query($q)
	{
		$objArray = array();
		if($result = $this->connection->query($q))
		{
			while($obj = $result->fetch_object())
		    {
				array_push($objArray,$obj);
		    }
			$result->close();
		}
		return (object) $objArray;
	}

	function singleRow($q)
	{
		$result = $this->connection->query($q);
		if(!is_bool($result))
		{
			return $result->fetch_object();
		}
	}

	function escape($s)
	{
		return $this->connection->real_escape_string($s);
	}

	function lastAdded()
	{
		return $this->connection->insert_id;
	}

	function __destruct()
	{
		$this->connection->close();
	}
}
?>
