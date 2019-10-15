<?php

class Home {
	private $self_file = 'home_core.php';
	private $mysqli = false;
	private $session = false;
	
	public function __construct($m) { $this->mysqli = $m; }
	
	public function set_session_obj($obj) { $this->session = $obj; }
	
	public function get_new_items($interval) {
		if($interval == 'today')
			$q = "SELECT count(*) as c FROM invento_items WHERE DATE(date_added) = DATE(NOW())";
		elseif($interval == 'this_week')
			$q = "SELECT count(*) as c FROM invento_items WHERE date_added > DATE_SUB(NOW(), INTERVAL 1 WEEK)";
		elseif($interval == 'this_month')
			$q = "SELECT count(*) as c FROM invento_items WHERE date_added > DATE_SUB(NOW(), INTERVAL 1 MONTH)";
		elseif($interval == 'this_year')
			$q = "SELECT count(*) as c FROM invento_items WHERE date_added > DATE_SUB(NOW(), INTERVAL 1 YEAR)";
		elseif($interval == 'all_time')
			$q = "SELECT count(*) as c FROM invento_items";
		
		$res = $this->query($q, 'get_new_items()');
		$obj = $res->fetch_object();
		return $obj->c;
	}
	
	
	public function get_checked_in($interval) {
		if($interval == 'today')
			$q = "SELECT SUM(toqty - fromqty) as s FROM invento_logs WHERE `type`=1 AND DATE(date_added) = DATE(NOW())";
		elseif($interval == 'this_week')
			$q = "SELECT SUM(toqty - fromqty) as s FROM invento_logs WHERE `type`=1 AND date_added > DATE_SUB(NOW(), INTERVAL 1 WEEK)";
		elseif($interval == 'this_month')
			$q = "SELECT SUM(toqty - fromqty) as s FROM invento_logs WHERE `type`=1 AND date_added > DATE_SUB(NOW(), INTERVAL 1 MONTH)";
		elseif($interval == 'this_year')
			$q = "SELECT SUM(toqty - fromqty) as s FROM invento_logs WHERE `type`=1 AND date_added > DATE_SUB(NOW(), INTERVAL 1 YEAR)";
		elseif($interval == 'all_time')
			$q = "SELECT SUM(toqty - fromqty) as s FROM invento_logs WHERE `type`=1";
		
		$res = $this->query($q, 'get_checked_in()');
		$obj = $res->fetch_object();
		if($obj->s == '')
			return 0;
		return $obj->s;
	}
	
	public function get_checked_out($interval) {
		if($interval == 'today')
			$q = "SELECT SUM(fromqty - toqty) as s FROM invento_logs WHERE `type`=2 AND DATE(date_added) = DATE(NOW())";
		elseif($interval == 'this_week')
			$q = "SELECT SUM(fromqty - toqty) as s FROM invento_logs WHERE `type`=2 AND date_added > DATE_SUB(NOW(), INTERVAL 1 WEEK)";
		elseif($interval == 'this_month')
			$q = "SELECT SUM(fromqty - toqty) as s FROM invento_logs WHERE `type`=2 AND date_added > DATE_SUB(NOW(), INTERVAL 1 MONTH)";
		elseif($interval == 'this_year')
			$q = "SELECT SUM(fromqty - toqty) as s FROM invento_logs WHERE `type`=2 AND date_added > DATE_SUB(NOW(), INTERVAL 1 YEAR)";
		elseif($interval == 'all_time')
			$q = "SELECT SUM(fromqty - toqty) as s FROM invento_logs WHERE `type`=2";

		$res = $this->query($q, 'get_checked_out()');
		$obj = $res->fetch_object();
		if($obj->s == '')
			return 0;
		return $obj->s;
	}
	
	
	public function get_checked_in_price($interval) {
		if($interval == 'today')
			$q = "SELECT SUM(fromprice) as s FROM invento_logs WHERE `type`=1 AND DATE(date_added) = DATE(NOW())";
		elseif($interval == 'this_week')
			$q = "SELECT SUM(fromprice) as s FROM invento_logs WHERE `type`=1 AND date_added > DATE_SUB(NOW(), INTERVAL 1 WEEK)";
		elseif($interval == 'this_month')
			$q = "SELECT SUM(fromprice) as s FROM invento_logs WHERE `type`=1 AND date_added > DATE_SUB(NOW(), INTERVAL 1 MONTH)";
		elseif($interval == 'this_year')
			$q = "SELECT SUM(fromprice) as s FROM invento_logs WHERE `type`=1 AND date_added > DATE_SUB(NOW(), INTERVAL 1 YEAR)";
		elseif($interval == 'all_time')
			$q = "SELECT SUM(fromprice) as s FROM invento_logs WHERE `type`=1";
		
		$res = $this->query($q, 'get_checked_in_price()');
		$obj = $res->fetch_object();
		if($obj->s == '')
			return 0;
		return $this->parse_cost($obj->s);
	}
	
	public function get_checked_out_price($interval) {
		if($interval == 'today')
			$q = "SELECT SUM(fromprice) as s FROM invento_logs WHERE `type`=2 AND DATE(date_added) = DATE(NOW())";
		elseif($interval == 'this_week')
			$q = "SELECT SUM(fromprice) as s FROM invento_logs WHERE `type`=2 AND date_added > DATE_SUB(NOW(), INTERVAL 1 WEEK)";
		elseif($interval == 'this_month')
			$q = "SELECT SUM(fromprice) as s FROM invento_logs WHERE `type`=2 AND date_added > DATE_SUB(NOW(), INTERVAL 1 MONTH)";
		elseif($interval == 'this_year')
			$q = "SELECT SUM(fromprice) as s FROM invento_logs WHERE `type`=2 AND date_added > DATE_SUB(NOW(), INTERVAL 1 YEAR)";
		elseif($interval == 'all_time')
			$q = "SELECT SUM(fromprice) as s FROM invento_logs WHERE `type`=2";
		
		$res = $this->query($q, 'get_checked_out_price()');
		$obj = $res->fetch_object();
		if($obj->s == '')
			return 0;
		return $this->parse_cost($obj->s);
	}
	
	
	public function general_registered_items() {
		$res = $this->query("SELECT COUNT(*) as c FROM invento_items", 'general_registered_items()');
		$obj = $res->fetch_object();
		return $obj->c;
	}
	
	
	public function general_warehouse_items() {
		$res = $this->query("SELECT SUM(qty) as s FROM invento_items", 'general_warehouse_items()');
		$obj = $res->fetch_object();
		if($obj->s == '')
			return 0;
		return $obj->s;
	}
	
	
	public function general_warehouse_value() {
		$res = $this->query("SELECT SUM(qty*price) as s FROM invento_items", 'general_warehouse_value()');
		$obj = $res->fetch_object();
		if($obj->s == '')
			return 0;
		return $this->parse_cost($obj->s);
	}
	
	public function general_warehouse_checked_out() {
		$res = $this->query("SELECT SUM((fromqty-toqty)*fromprice) as s FROM invento_logs WHERE `type`=2", 'general_warehouse_checked_out');
		$obj = $res->fetch_object();
		if($obj->s == '')
			return 0;
		return $this->parse_cost($obj->s);
	}
	
	public function parse_cost($p) {
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

$_home = new Home($mysqli);