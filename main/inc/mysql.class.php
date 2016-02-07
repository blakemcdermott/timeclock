<?PHP
class mysql {
	var $linkid, $host, $user, $pswd, $db, $result, $querycount;
	
/* Class constructor. Initializes the $host, $user, $pswd and $db fields. */
function __construct($host, $user, $pswd, $db) {
	$this->host = $host;
	$this->user = $user;
	$this->pswd = $pswd;
	$this->db = $db;
}

/* Connects to the MySQL database server. */
function connect() {
	try {
		$this->linkid = @mysql_pconnect($this->host,$this->user,$this->pswd); 
		if (! $this->linkid)
		throw new Exception("Could not connect to the MySQL server.");
		}
		catch (Exception $e) {
			die($e->getMessage());
		}
}

/* This closes the database connection. */
function close() {
	$this->close = @mysql_close($this->linkid);
	if($this->close) {
		$message = "The MySQL connection has been closed.";
		return $message;
		}
	}
			
/* Selects MySQL Database */
function select() {
	try {
		if (! @mysql_select_db($this->db, $this->linkid))
		throw new Exception("Could not select the".$this->db." database.");
		}
		catch (Exception $e) {
			die($e->getMessage());
		}
	}

/* Execute database query */
function query($query){
	try {
		$this->result = mysql_query($query,$this->linkid);
		if (! $this->result)
		throw new Exception("The database query failed.");
		}
	catch (Exception $e) {
		die($e->getMessage());
		}
	$this->querycount++;
	return $this->result;
	}
	
/* Determine total rows affected by query. */
function affectedRows() {
	$count = @mysql_affected_rows($this->linkid);
	return $count;
	}

/* Determine total rows returned by query. */
function numRows() {
	$count = @mysql_num_rows($this->result);
	return $count;
	}

/* Return query result row as an object. */
function fetchObject() {
	$row = @mysql_fetch_object($this->result);
	return $row;
	}

/* Return query result as an indexed array. */
function fetchRow() {
	$row = @mysql_fetch_row($this->result);
	return $row;
	}

/* Return query result row as an associative array. */
function fetchArray() {
	$row = @mysql_fetch_array($this->result);
	return $row;
	}

/* Return total number of queries executed during lifetime of this object. */
function numQueries() {
	return $this->querycount;
	}
/* Counts the total number of records within the given table */
function countRecords($tblselect) {
	$sql = "SELECT COUNT(*) FROM $tblselect";
	$this->result = $this->query($sql);
	
	echo $this->result;
	}
}
?>