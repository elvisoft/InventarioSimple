<?php

class Session {
	private $self_file = 'session.php';
	private $mysqli = false;
	
	public function __construct($m) { $this->mysqli = $m; }
	
	public function isLogged() {
		if(!isset($_SESSION['invento_logged']) || !is_array($_SESSION['invento_logged']))
			return false;
		
		if(!isset($_SESSION['invento_logged']['u']) || !isset($_SESSION['invento_logged']['p']))
			return false;
		
		$u = $_SESSION['invento_logged']['u'];
		$p = $_SESSION['invento_logged']['p'];
		
		$prepared = $this->prepare("SELECT count(*) as c FROM invento_users WHERE username=? && password=?", 'isLogged()');
		$this->bind_param($prepared->bind_param('ss', $u, $p), 'isLogged()');
		$this->execute($prepared, 'isLogged()');
		
		$result = $prepared->get_result();
		$row = $result->fetch_object();
		
		if($row->c == 1)
			return true;
		return false;
	}
	
	public function refresh_password($pass) {
		$_SESSION['invento_logged']['p'] = md5($pass);
		return true;
	}
	
	public function login($u, $p) {
		$p = md5($p);
		
		$prepared = $this->prepare("SELECT count(*) as c FROM invento_users WHERE username=? && password=?", 'isLogged()');
		$this->bind_param($prepared->bind_param('ss', $u, $p), 'login()');
		$this->execute($prepared, 'login()');
		
		$result = $prepared->get_result();
		$row = $result->fetch_object();
		
		if($row->c != 1)
			return false;
			
		$_SESSION['invento_logged']['u'] = $u;
		$_SESSION['invento_logged']['p'] = $p;
		
		return true;
	}
	
	public function logout() {
		if(isset($_SESSION['invento_logged']))
			$_SESSION['invento_logged'] = false;
		unset($_SESSION);
		session_destroy();
		return true;
	}
	
	public function get_user_id() {
		$username = $_SESSION['invento_logged']['u'];
		
		$prepared = $this->prepare("SELECT id FROM invento_users WHERE username=?", 'get_user_id()');
		$this->bind_param($prepared->bind_param('s', $username), 'get_user_id()');
		$this->execute($prepared, 'get_user_id()');
		
		$result = $prepared->get_result();
		$row = $result->fetch_object();
		
		return $row->id;
	}
	
	public function get_user_name_by_id($id) {
		$prepared = $this->prepare("SELECT username FROM invento_users WHERE id=?", 'get_user_name_by_id()');
		$this->bind_param($prepared->bind_param('i', $id), 'get_user_name_by_id()');
		$this->execute($prepared, 'get_user_name_by_id()');
		
		$result = $prepared->get_result();
		$row = $result->fetch_object();
		
		return $row->username;
	}
	
	public function get_user_role() {
		$id = $this->get_user_id();
		
		$prepared = $this->prepare("SELECT role FROM invento_users WHERE id=?", 'get_user_role()');
		$this->bind_param($prepared->bind_param('i', $id), 'get_user_role()');
		$this->execute($prepared, 'get_user_role()');
		
		$result = $prepared->get_result();
		$row = $result->fetch_object();
		
		return $row->role;
	}
	
	
	/***
	  *  Private functions
	  *
	***/
	private function prepare($query, $func) {
		$prepared = $this->mysqli->prepare($query);
		if(!$prepared)
			die("Couldn't prepare query. inc/{$this->self_file} - $func");
		return $prepared;
	}
	private function bind_param($param, $func) {
		if(!$param)
			die("Couldn't bind parameters. inc/{$this->self_file} - $func");
		return $param;
	}
	private function execute($prepared, $func) {
		$exec = $prepared->execute();
		if(!$exec)
			die("Couldn't execute query. inc/{$this->self_file} - $func");
		return $exec;
	}
	private function query($query, $func) {
		$q = $this->mysqli->query($query);
		if(!$q)
			die("Couldn't run query. inc/{$this->self_file} - $func");
		return $q;
	}
	public function __destruct() {
		if(is_resource($this->mysqli) && get_resource_type($this->mysqli) == 'mysql link')
			$this->mysqli->close();
	}
}

$_session = new Session($mysqli);