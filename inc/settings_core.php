<?php

class Settings {
	private $self_file = 'settings_core.php';
	private $mysqli = false;
	private $session = false;
	
	public function __construct($m) { $this->mysqli = $m; }
	
	public function set_session_obj($obj) { $this->session = $obj; }
	
	public function get_setting($setting) {
		$prepared = $this->prepare("SELECT val FROM invento_settings WHERE name=?", 'get_setting()');
		$this->bind_param($prepared->bind_param('s', $setting), 'get_setting()');
		$this->execute($prepared, 'get_setting()');
		
		$result = $prepared->get_result();
		$row = $result->fetch_object();
		
		return $row->val;
	}
	
	public function update_setting($setting, $val) {
		$prepared = $this->prepare("UPDATE invento_settings SET val=? WHERE name=?", 'update_setting()');
		$this->bind_param($prepared->bind_param('ss', $val, $setting), 'update_setting()');
		$this->execute($prepared, 'update_setting()');
		return true;
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

$_settings = new Settings($mysqli);