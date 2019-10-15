<?php

class Logs {
	private $self_file = 'logs_core.php';
	private $mysqli = false;
	private $session = false;
	
	public function __construct($m) { $this->mysqli = $m; }
	
	public function set_session_obj($obj) { $this->session = $obj; }
	
	public function get_logs($page, $items_per_page, $itemid, $catid, $userid) {
		if($page == 0 || $page == 1)
			$x = 0;
		else
			$x = ($items_per_page * ($page-1));
		$y = $items_per_page;
		
		if($itemid != false) {
			$prepared = $this->prepare("SELECT * FROM invento_logs WHERE item=? ORDER BY id DESC LIMIT ?,?", 'get_logs()');
			$this->bind_param($prepared->bind_param('iii', $itemid, $x, $y), 'get_logs()');
		}elseif($catid != false){
			$prepared = $this->prepare("SELECT * FROM invento_logs WHERE item IN (SELECT id FROM invento_items WHERE category=?) ORDER BY id DESC LIMIT ?,?", 'get_logs()');
			$this->bind_param($prepared->bind_param('iii', $catid, $x, $y), 'get_logs()');
		}elseif($userid != false){
			$prepared = $this->prepare("SELECT * FROM invento_logs WHERE `user`=? ORDER BY id DESC LIMIT ?,?", 'get_logs()');
			$this->bind_param($prepared->bind_param('iii', $userid, $x, $y), 'get_logs()');
		}else{
			$prepared = $this->prepare("SELECT * FROM invento_logs ORDER BY id DESC LIMIT ?,?", 'get_logs()');
			$this->bind_param($prepared->bind_param('ii', $x, $y), 'get_logs()');
		}
		
		$this->execute($prepared, 'get_logs()');
		$result = $prepared->get_result();
		return $result;
	}
	
	public function search($string, $page, $items_per_page, $itemid, $catid, $userid) {
		$s = "%$string%";
		if($page == 0 || $page == 1)
			$x = 0;
		else
			$x = ($items_per_page * ($page-1));
		$y = $items_per_page;
		
		if($itemid != false){
			$prepared = $this->prepare("SELECT * FROM invento_logs WHERE item=? AND (id LIKE ? OR item IN (SELECT id FROM invento_items WHERE name LIKE ?) OR `user` IN (SELECT id FROM invento_users WHERE username LIKE ?) OR date_added LIKE ?) ORDER BY id DESC LIMIT ?,?", 'search()');
			$this->bind_param($prepare->bind_param('issssii', $itemid, $s, $s, $s, $s, $x, $y), 'search()');
		}elseif($catid != false){
			$prepared = $this->prepare("SELECT * FROM invento_logs WHERE item IN (SELECT id FROM invento_items WHERE category=?) AND (id LIKE ? OR item IN (SELECT id FROM invento_items WHERE name LIKE ?) OR `user` IN (SELECT id FROM invento_users WHERE username LIKE ?) OR date_added LIKE ?) ORDER BY id DESC LIMIT ?,?", 'search()');
			$this->bind_param($prepare->bind_param('issssii', $catid, $s, $s, $s, $s, $x, $y), 'search()');
		}elseif($userid != false){
			$prepared = $this->prepare("SELECT * FROM invento_logs WHERE `user`=? AND (id LIKE ? OR item IN (SELECT id FROM invento_items WHERE name LIKE ?) OR `user` IN (SELECT id FROM invento_users WHERE username LIKE ?) OR date_added LIKE ?) ORDER BY id DESC LIMIT ?,?", 'search()');
			$this->bind_param($prepare->bind_param('issssii', $userid, $s, $s, $s, $s, $x, $y), 'search()');
		}else{
			$prepared = $this->prepare("SELECT * FROM invento_logs WHERE id LIKE ? OR item IN (SELECT id FROM invento_items WHERE name LIKE ?) OR `user` IN (SELECT id FROM invento_users WHERE username LIKE ?) OR date_added LIKE ? ORDER BY id DESC LIMIT ?,?", 'search()');
			$this->bind_param($prepared->bind_param('ssssii', $s, $s, $s, $s, $x, $y), 'search()');
		}
		
		$this->execute($prepared, 'search()');
		$result = $prepared->get_result();
		return $result;
	}
	
	public function count_logs($itemid, $catid, $userid) {
		if($itemid != false){
			$prepared = $this->prepare("SELECT COUNT(*) as c FROM invento_logs WHERE item=?", 'count_logs()');
			$this->bind_param($prepared->bind_param('i', $itemid), 'count_logs()');
		}elseif($catid != false){
			$prepared = $this->prepare("SELECT COUNT(*) as c FROM invento_logs WHERE item IN (SELECT id FROM invento_items WHERE category=?)", 'count_logs()');
			$this->bind_param($prepared->bind_param('i', $catid), 'count_logs()');
		}elseif($userid != false){
			$prepared = $this->prepare("SELECT COUNT(*) as c FROM invento_logs WHERE `user`=?", 'count_logs()');
			$this->bind_param($prepared->bind_param('i', $userid), 'count_logs()');
		}else{
			$res = $this->query("SELECT COUNT(*) as c FROM invento_logs", 'count_logs()');
			$obj = $res->fetch_object();
			
			return $obj->c;
		}
		
		$this->execute($prepared, 'count_logs()');
		
		$result = $prepared->get_result();
		$row = $result->fetch_object();
		
		return $row->c;
	}
	
	public function count_logs_search($string, $itemid, $catid, $userid) {
		$s = "%$string%";
		if($itemid != false){
			$prepared = $this->prepare("SELECT COUNT(*) as c FROM invento_logs WHERE item=? AND (id LIKE ? OR item IN (SELECT id FROM invento_items WHERE name LIKE ?) OR `user` IN (SELECT id FROM invento_users WHERE username LIKE ?) OR date_added LIKE ?)", 'count_logs_search()');
			$this->bind_param($prepare->bind_param('issss', $itemid, $s, $s, $s, $s), 'count_logs_search()');
		}elseif($catid != false){
			$prepared = $this->prepare("SELECT COUNT(*) as c FROM invento_logs WHERE item IN (SELECT id FROM invento_items WHERE category=?) AND (id LIKE ? OR item IN (SELECT id FROM invento_items WHERE name LIKE ?) OR `user` IN (SELECT id FROM invento_users WHERE username LIKE ?) OR date_added LIKE ?)", 'count_logs_search()');
			$this->bind_param($prepare->bind_param('issss', $catid, $s, $s, $s, $s), 'count_logs_search()');
		}elseif($userid != false){
			$prepared = $this->prepare("SELECT COUNT(*) as c FROM invento_logs WHERE `user`=? AND (id LIKE ? OR item IN (SELECT id FROM invento_items WHERE name LIKE ?) OR `user` IN (SELECT id FROM invento_users WHERE username LIKE ?) OR date_added LIKE ?)", 'count_logs_search()');
			$this->bind_param($prepare->bind_param('issss', $userid, $s, $s, $s, $s), 'count_logs_search()');
		}else{
			$prepared = $this->prepare("SELECT COUNT(*) as c FROM invento_logs WHERE id LIKE ? OR item IN (SELECT id FROM invento_items WHERE name LIKE ?) OR `user` IN (SELECT id FROM invento_users WHERE username LIKE ?) OR date_added LIKE ?", 'count_logs_search()');
			$this->bind_param($prepared->bind_param('ssss', $s, $s, $s, $s), 'count_logs_search()');
		}
		
		$this->execute($prepared, 'count_logs_search()');
		
		$result = $prepared->get_result();
		$row = $result->fetch_object();
		
		return $row->c;
	}
	
	public function parse_price($p) {
		return $p;
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

$_logs = new Logs($mysqli);