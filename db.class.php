<?php

/*

DB類

*/

class DB {
	var $host;//主機地址
	var $username;//用戶名
	var $password;//密碼
	var $dbname;//數據庫名
	var $port = 3306;
	var $charset;//字符集 編碼類型
	private $con;//連接對象
	
	/**
	 * 構造函數
	 */
	//private function __construct($config) {
	public function __construct($config=array()) {
		$this->host = isset($config['db_host']) ? $config['db_host'] : 'localhost';
		$this->username = isset($config['db_username']) ? $config['db_username'] : 'root';
		$this->password = isset($config['db_password']) ? $config['db_password'] : '';
		$this->dbname = isset($config['db_dbname']) ? $config['db_dbname'] : 'test';
		$this->charset = isset($config['db_charset']) ? $config['db_charset'] : 'utf8';
		//連接數據庫
		$this->connect();
		$this->use_db();
		$this->set_charset();
	}
	
	/**
	 * 連接數據庫
	 */
	public function connect() {
		$con = mysqli_connect($this->host.':'.$this->port,$this->username,$this->password) or die("連接數據庫失敗");
		$this->con = $con;
		return $con;
	}
	
	/**
	 * 執行語句
	 */
	public function query($sql) {
		$res = mysqli_query($this->con,$sql);
		if($res) {
			//$this->resource = $res;
			return $res;
		} else {
			echo '語句 '.$sql.' 出錯. ';
			echo mysqli_error($this->con);
			exit;
		}
	}
	
	/**
	 * 設置字符集/字符編碼
	 */
	public function set_charset($charset='') {
		$sql = "SET names '".$this->charset."'";
		if(!empty($charset)) {
			$sql = "SET names '".$charset."'";
		}
		$this->query($sql) or die('set_charset');
	}
	
	/**
	 * 設置數據庫
	 */
	public function use_db($dbname='') {
		$sql = "USE ".$this->dbname."";
		if(!empty($dbname)) $sql = "USE ".$dbname."";
		$this->query($sql) or die('use_db');
	}
	
	/**
	 * 獲取數據(一行)
	 */
	public function get_row($sql,$result_type=MYSQLI_ASSOC) {
		$rec = $this->query($sql);
		if(!$rec) $rec = array();
		return mysqli_fetch_array($rec,$result_type);
	}
	
	/**
	 * 獲取數據(多行)
	 */
	public function get_rows($sql,$result_type=MYSQLI_ASSOC) {
		$rec = $this->query($sql);
		$arr = array();
		while($res = mysqli_fetch_array($rec,$result_type)) {
			if($res) $arr[] = $res;
		}
		return $arr;
	}
	
	/**
	 * 獲取最後插入ID
	 */
	public function insert_id() {
		return mysqli_insert_id($this->con);
	}
	
	
	public function f($str) {
		//return htmlspecialchars(addslashes($str));
		if($str === null) {
			return $str;
		}
		return mysqli_real_escape_string($this->con,$str);
	}
	public function f2($str) {
		return stripslashes(htmlspecialchars_decode($str));
	}
	
	public function ping() {
		return mysqli_ping($this->con);
	}
	
	public function close() {
		mysqli_close($this->con);
	}
	
	public function check() {
		mysqli_connect_errno($this->con);
	}
}
