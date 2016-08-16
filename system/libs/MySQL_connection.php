<?php
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }

	class MySQLConnection {
		private $sqlHost;
		private $sqlUser;
		private $sqlPassword;
		private $sqlDatabase;
		private $mySqlLinkIdentifier = FALSE;
		public $QueryFetchArrayTemp = array();
		private $numQueries = 0;
		public $UsedTime = 0;

		public function __construct($sqlHost, $sqlUser, $sqlPassword, $sqlDatabase = FALSE) {
			$this->sqlHost = $sqlHost;
			$this->sqlUser = $sqlUser;
			$this->sqlPassword = $sqlPassword;
			$this->sqlDatabase = $sqlDatabase;
		}

		public function __destruct() {
			$this->Close();
		}

		public function Connect() {
			if($this->mySqlLinkIdentifier !== FALSE) {
				return $this->mySqlLinkIdentifier;
			}

			$this->mySqlLinkIdentifier = mysql_connect($this->sqlHost, $this->sqlUser, $this->sqlPassword, TRUE); // Open new link on every call
			if($this->mySqlLinkIdentifier === FALSE) {
				return FALSE;
			}

			if($this->sqlDatabase !== FALSE) {
				mysql_select_db($this->sqlDatabase, $this->mySqlLinkIdentifier);
			}

			return $this->mySqlLinkIdentifier;
		}

		public function Close() {
			if($this->mySqlLinkIdentifier !== FALSE) {
				mysql_close($this->mySqlLinkIdentifier);
				$this->mySqlLinkIdentifier = FALSE;
			}
		}

		public function GetLinkIdentifier() {
			return $this->mySqlLinkIdentifier;
		}		

		public function Query($query) {
			$start = microtime(true);
			$result = mysql_query($query, $this->GetLinkIdentifier());
			$this->UsedTime += microtime(true) - $start;
			$this->numQueries++;

			if( $result === false ){
				die($this->GetErrorMessage());
			}

			return $result;
		}

		public function FreeResult($result) {
			mysql_free_result($result);
		}

		public function FetchArray($result) {
			return mysql_fetch_array($result, MYSQL_ASSOC);
		}

		public function FetchArrayAll($result){
			$retval = array();
			if($this->GetNumRows($result)) {
				while($row = $this->FetchArray($result)) {
					$retval[] = $row;
				}			
			}
			return $retval;
		}	

		public function GetNumRows($result) {
			return mysql_num_rows($result);
		}

		public function QueryGetNumRows($query) {
			$query = $this->Query($query);
			$result = mysql_num_rows($query);
			$this->FreeResult($query);

			return $result;
		}

		public function GetNumAffectedRows() {
			return mysql_affected_rows($this->mySqlLinkIdentifier);
		}

		public function QueryFetchArrayAll($query) {
			$result = $this->Query($query);
			if($result === FALSE) {
				return FALSE;
			}

			$retval = $this->FetchArrayAll($result);
			$this->FreeResult($result);

			return $retval;			
		}

		public function QueryFirstRow($query) {
			$result = $this->Query($query);
			if($result === FALSE) {
				return FALSE;
			}

			$retval = FALSE;

			$row = $this->FetchArray($result);
			if($row !== FALSE) {
				$retval = $row;
			}

			$this->FreeResult($result);

			return $retval;		
		}

		public function QueryFirstValue($query) {
			$row = $this->QueryFirstRow($query);
			if($row === FALSE) {
				return FALSE;
			}

			return $row[0];			
		}

		public function GetErrorMessage() {
			return "SQL Error: ".mysql_error().": ";
		}

		public function EscapeString($string) {
			if (is_array($string))
			{
				$str = array();
				foreach ($string as $key => $value)
				{
					$str[$key] = $this->EscapeString($value);
				}

				return $str;
			}

			return get_magic_quotes_gpc() ? mysql_real_escape_string(stripslashes($string), $this->mySqlLinkIdentifier) : mysql_real_escape_string($string, $this->mySqlLinkIdentifier);
		}

		function GetNumberOfQueries() {
			return $this->numQueries;
		}

		public function BeginTransaction() {
			$this->Query("SET AUTOCOMMIT=0");
			$this->Query("BEGIN");
		}

		public function CommitTransaction() {
			$this->Query("COMMIT");
			$this->Query("SET AUTOCOMMIT=1");
		}

		public function RollbackTransaction() {
			$this->Query("ROLLBACK");
			$this->Query("SET AUTOCOMMIT=1");
		}

		public function GetFoundRows() {
			return $this->QueryFirstValue("SELECT FOUND_ROWS()");
		}

		public function GetLastInsertId() {
			return $this->QueryFirstValue("SELECT LAST_INSERT_ID()");			
		}

		public function QueryFetchArray($query)
		{
			$query = $this->Query($query);
			$result = $this->FetchArray($query);
			$this->FreeResult($query);

			return $result;
		}
	}
?>