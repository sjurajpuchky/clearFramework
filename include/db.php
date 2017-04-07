<?php

namespace cf;

define ( 'DB_TYPE_MYSQL', 1 );
define ( 'DB_TYPE_MSSQL', 2 );
define ( 'DB_TYPE_MYSQLI', 3 );
class Db {
	private $link;
	public $table_prefix;
	private $db_type;
	public $oCache;
	function __construct($server = DB_HOST, $username = DB_USER, $password = DB_PASSWORD, $database_name = DB_NAME, $table_prefix = DB_TABLE_PREFIX, $db_type = DB_TYPE_MYSQL) {
		$this->db_type = $db_type;
		switch ($this->db_type) {
			
			case DB_TYPE_MYSQL :
				$this->link = mysql_connect ( $server, $username, $password, true );
				if (! $this->link) {
					die ( "Cannot connect to DB" );
				}
				if (! mysql_select_db ( $database_name, $this->link )) {
					die ( "Cannot select db" );
				}
				mysql_set_charset ( 'utf8', $this->link );
				break;
			case DB_TYPE_MYSQLI :
				$this->link = mysqli_connect ( $server, $username, $password, $database_name );
				if (! $this->link) {
					die ( "Cannot connect to DB" );
				}
				break;
			case DB_TYPE_MSSQL :
				$this->link = mssql_connect ( $server, $username, $password, true );
				if (! $this->link) {
					die ( "Cannot connect to DB" );
				}
				if (! mssql_select_db ( $database_name, $this->link )) {
					die ( "Cannot select db" );
				}
				break;
		}
		$this->table_prefix = $table_prefix;
		$this->oCache = new \Memcache ();
		$this->oCache->connect ( MEMCACHED_SERVER, MEMCACHED_PORT ) or die ( 'Could not connect to Memcahced server' );
	}
	public function getResults($sql, $type = MYSQL_ASSOC, $debug = false, $skipCache = true) {
		$key = md5 ( $sql );
		$cache_result = $this->oCache->get ( $key );
		if ($cache_result && !$skipCache) {
			return $cache_result;
		} else {
			if ($debug) {
				echo $this->db_type . ":" . $sql . "<br>";
				echo "Cached as $key<br>";
			}
			$result = array ();
			switch ($this->db_type) {
				case DB_TYPE_MYSQL :
					$res = mysql_query ( $sql, $this->link );
					if ($res) {
						if ($debug) {
							// print_r($res);
							echo "Rows:" . mysql_num_rows ( $res ) . "<br>";
							echo mysql_error ( $this->link );
						}
						while ( $row = mysql_fetch_array ( $res, $type ) ) {
							$result [] = $row;
							if ($debug) {
								print_r ( $row );
							}
						}
					} else {
						if ($debug) {
							echo mysql_error ( $this->link );
						}
					}
					break;
				case DB_TYPE_MYSQLI :
					$res = mysqli_query ( $this->link, $sql );
					if ($res) {
						while ( $row = mysqli_fetch_array ( $res, $type ) ) {
							$result [] = $row;
						}
					}
					break;
				case DB_TYPE_MSSQL :
					$res = mssql_query ( $sql, $this->link );
					if ($res) {
						while ( $row = mssql_fetch_array ( $res, $type ) ) {
							$result [] = $row;
						}
					}
					break;
			}
			
			$this->oCache->set ( $key, $result, MEMCACHE_COMPRESSED, 3600 );
		}
		
		return $result;
	}
	public function query($sql) {
		switch ($this->db_type) {
			case DB_TYPE_MYSQL :
				$res = mysql_query ( $sql, $this->link );
				break;
			case DB_TYPE_MYSQLI :
				$res = mysqli_query ( $this->link, $sql );
				break;
			case DB_TYPE_MSSQL :
				$res = mssql_query ( $sql, $this->link );
				break;
		}
		return $res;
	}
	public function insert($table, $assoc) {
		$cols = "";
		$values = "";
		foreach ( $assoc as $key => $value ) {
			$cols .= "`$key`,";
			if ($value == "NOW()") {
				$values .= addslashes ( $value ) . ",";
			} else {
				$values .= "'" . addslashes ( $value ) . "',";
			}
		}
		$cols = trim ( $cols, "," );
		$values = trim ( $values, "," );
		$sql = "INSERT INTO $table($cols) VALUES($values);";
		// echo $sql;
		return $this->query ( $sql );
	}
	public function update($table, $assoc, $condition, $debug = false) {
		$sets = "";
		foreach ( $assoc as $key => $value ) {
			if ($value == "NOW()") {
				$sets .= " `$key`=" . addslashes ( $value ) . ",";
			} else {
				$sets .= " `$key`='" . addslashes ( $value ) . "',";
			}
			
		}
		$sets = trim ( $sets, "," );
		$sql = "UPDATE $table SET $sets WHERE $condition;";
		if ($debug) {
			echo $sql;
		}
		return $this->query ( $sql );
	}
	
	/**
	 * Convert meta table to associative array
	 *
	 * @param Array $metas        	
	 * @param string $metakey        	
	 */
	function metaAsArray($metas, $metakey = "metakey", $metavalue = "metavalue") {
		$ret = array ();
		foreach ( $metas as $meta ) {
			$ret [$meta [$metakey]] = $meta [$metavalue];
		}
		return $ret;
	}
	
	/**
	 * Last inserted id
	 *
	 * @return number
	 */
	public function getLastId() {
		switch ($this->db_type) {
			case DB_TYPE_MYSQL :
				$id = mysql_insert_id ( $this->link );
				break;
			case DB_TYPE_MSSQL :
				$id = 0;
				error_log ( "getLastId is not supported yet for MSSQL" );
				break;
			case DB_TYPE_MYSQLI :
				$id = mysqli_insert_id ( $this->link );
				break;
		}
		return $id;
	}
	function __destruct() {
		switch ($this->db_type) {
			case DB_TYPE_MYSQL :
				if ($this->link) {
					mysql_close ( $this->link );
				}
				break;
			case DB_TYPE_MSSQL :
				mssql_close ( $this->link );
				break;
			case DB_TYPE_MYSQLI :
				mysqli_close ( $this->link );
				break;
		}
		unset ( $this->link );
	}
	public function getLastError() {
		switch ($this->db_type) {
			case DB_TYPE_MYSQL :
				$err = mysql_error ( $this->link );
				break;
			case DB_TYPE_MSSQL :
				$err = mssql_get_last_message ();
				break;
			case DB_TYPE_MYSQLI :
				$err = mysqli_error ( $this->link );
		}
		return $err;
	}
	function getLink() {
		return $this->link;
	}
	function getTable_prefix() {
		return $this->table_prefix;
	}
	function setLink($link) {
		$this->link = $link;
	}
	function setTable_prefix($table_prefix) {
		$this->table_prefix = $table_prefix;
	}
}
