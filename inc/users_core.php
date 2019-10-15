<?php

class Users {
	private $self_file = 'users_core.php';
	private $mysqli = false;
	private $session = false;
	
	public function __construct($m) { $this->mysqli = $m; }
	
	public function set_session_obj($obj) { $this->session = $obj; }
	
	public function get_users($page, $items_per_page) {
		if($page == 0 || $page == 1)
			$x = 0;
		else
			$x = ($items_per_page * ($page-1));
		$y = $items_per_page;
		
		// Admin = All Users
		// General Supervisors = Employees and Supervisors
		// Supervisor = Employees
		// Employees = None
		$role = $this->session->get_user_role();
		if($role == 1)
			$q = $this->query("SELECT * FROM invento_users ORDER BY id DESC LIMIT $x,$y", 'get_users()');
		elseif($role == 2)
			$q = $this->query("SELECT * FROM invento_users WHERE role=3 OR role=4 ORDER BY id DESC LIMIT $x,$y", 'get_users()');
		elseif($role == 3)
			$q = $this->query("SELECT * FROM invento_users WHERE role=4 ORDER BY id DESC LIMIT $x,$y", 'get_users()');
		else
			return false;
		
		return $q;
	}
	
	public function search($string, $page, $items_per_page) {
		$s = "%$string%";
		if($page == 0 || $page == 1)
			$x = 0;
		else
			$x = ($items_per_page * ($page-1));
		$y = $items_per_page;
		
		$role = $this->session->get_user_role();
		if($role == 1)
			$prepared = $this->prepare("SELECT * FROM invento_users WHERE username LIKE ? OR name LIKE ? OR email LIKE ? OR date_added LIKE ? ORDER BY id DESC LIMIT ?,?", 'search()');
		elseif($role == 2)
			$prepared = $this->prepare("SELECT * FROM invento_users WHERE (role=3 OR role=4) AND (username LIKE ? OR name LIKE ? OR email LIKE ? OR date_added LIKE ?) ORDER BY id DESC LIMIT ?,?", 'search()');
		elseif($role == 3)
			$prepared = $this->prepare("SELECT * FROM invento_users WHERE (role=4) AND (username LIKE ? OR name LIKE ? OR email LIKE ? OR date_added LIKE ?) ORDER BY id DESC LIMIT ?,?", 'search()');
		else
			return false;
		
		$this->bind_param($prepared->bind_param('ssssii', $s, $s, $s, $s, $x, $y), 'search()');
		$this->execute($prepared, 'search()');
		
		$result = $prepared->get_result();
		return $result;
	}
	
	public function count_users() {
		$role = $this->session->get_user_role();
		if($role == 1)
			$res = $this->query("SELECT COUNT(*) as c FROM invento_users", 'count_users()');
		elseif($role == 2)
			$res = $this->query("SELECT COUNT(*) as c FROM invento_users WHERE role=3 OR role=4", 'count_users()');
		elseif($role == 3)
			$res = $this->query("SELECT COUNT(*) as c FROM invento_users WHERE role=4", 'count_users()');
		else
			return 0;
		
		$obj = $res->fetch_object();
		return $obj->c;
	}
	
	public function count_users_search($string) {
		$s = "%$string%";
		
		$role = $this->session->get_user_role();
		if($role == 1)
			$prepared = $this->prepare("SELECT COUNT(*) as c FROM invento_users WHERE username LIKE ? OR name LIKE ? OR email LIKE ? OR date_added LIKE ?", 'count_users_search()');
		elseif($role == 2)
			$prepared = $this->prepare("SELECT COUNT(*) as c FROM invento_users WHERE (role=3 OR role=4) AND (username LIKE ? OR name LIKE ? OR email LIKE ? OR date_added LIKE ?)", 'count_users_search()');
		elseif($role == 3)
			$prepared = $this->prepare("SELECT COUNT(*) as c FROM invento_users WHERE role=4 AND (username LIKE ? OR name LIKE ? OR email LIKE ? OR date_added LIKE ?)", 'count_users_search()');
		else
			return 0;
		
		$this->bind_param($prepared->bind_param('ssss', $s, $s, $s, $s), 'count_users_search()');
		$this->execute($prepared, 'count_users_search()');
		
		$result = $prepared->get_result();
		$row = $result->fetch_object();
		
		return $row->c;
	}
	
	public function delete_user($userid) {
		$prepared = $this->prepare("DELETE FROM invento_users WHERE id=?", 'delete_user()');
		$this->bind_param($prepared->bind_param('i', $userid), 'delete_user()');
		$this->execute($prepared, 'delete_user()');
		return true;
	}
	
	public function new_user($name, $username, $password, $email, $role) {
		$prepared = $this->prepare("INSERT INTO invento_users(username,password,name,email,role,date_added) VALUES(?,?,?,?,?,?)", 'new_user()');
		$this->bind_param($prepared->bind_param('ssssis', $username, $password, $name, $email, $role, $date), 'delete_user()');
		$this->execute($prepared, 'new_user()');
		return true;
	}
	
	public function user_exists($username) {
		$prepared = $this->prepare("SELECT COUNT(*) as c FROM invento_users WHERE username=?", 'user_exists()');
		$this->bind_param($prepared->bind_param('s', $username), 'user_exists()');
		$this->execute($prepared, 'user_exists()');
		
		$result = $prepared->get_result();
		$row = $result->fetch_object();
		if($row->c >= 1)
			return true;
		return false;
	}
	
	public function userid_exists($userid) {
		$prepared = $this->prepare("SELECT COUNT(*) as c FROM invento_users WHERE id=?", 'userid_exists()');
		$this->bind_param($prepared->bind_param('s', $userid), 'userid_exists()');
		$this->execute($prepared, 'userid_exists()');
		
		$result = $prepared->get_result();
		$row = $result->fetch_object();
		if($row->c >= 1)
			return true;
		return false;
	}
	
	public function get_user($userid) {
		$prepared = $this->prepare("SELECT * FROM invento_users WHERE id=?", 'get_user()');
		$this->bind_param($prepared->bind_param('i', $userid), 'get_user()');
		$this->execute($prepared, 'get_user()');
		
		$result = $prepared->get_result();
		$row = $result->fetch_object();
		
		return $row;
	}
	
	public function edit_user($userid, $name, $email, $role) {
		$prepared = $this->prepare("UPDATE invento_users SET name=?, email=?, role=? WHERE id=?", 'edit_user()');
		$this->bind_param($prepared->bind_param('ssii', $name, $email, $role, $userid), 'edit_user()');
		$this->execute($prepared, 'edit_user()');
		return true;
	}
	
	public function update_user($userid, $name, $email) {
		if($name == false && $email == true) {
			$prepared = $this->prepare("UPDATE invento_users SET email=? WHERE id=?", 'update_user()');
			$this->bind_param($prepared->bind_param('si', $email, $userid), 'update_user()');
		}elseif($name == true && $email == false) {
			$prepared = $this->prepare("UPDATE invento_users SET name=? WHERE id=?", 'update_user()');
			$this->bind_param($prepared->bind_param('si', $name, $userid), 'update_user()');
		}elseif($name == true && $email == true) {
			$prepared = $this->prepare("UPDATE invento_users SET name=?, email=? WHERE id=?", 'update_user()');
			$this->bind_param($prepared->bind_param('ssi', $name, $email, $userid), 'update_user()');
		}
		
		$this->execute($prepared, 'update_user()');
		return true;
	}
	
	public function update_pass($userid, $pass) {
		$pass = md5($pass);
		$prepared = $this->prepare("UPDATE invento_users SET password=? WHERE id=?", 'update_pass()');
		$this->bind_param($prepared->bind_param('si', $pass, $userid), 'update_pass()');
		$this->execute($prepared, 'update_pass()');
		return true;
	}
	
	public function parse_role($role) {
		if($role == 1)
			return 'Administrator';
		elseif($role == 2)
			return 'General Supervisor';
		elseif($role == 3)
			return 'Supervisor';
		elseif($role == 4)
			return 'Employee';
		else
			return 'Undefined';
	}
	
	public function parse_date($date) {
		return date('d/m/Y', strtotime($date));
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

$_users = new Users($mysqli);