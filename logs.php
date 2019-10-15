<?php
require 'config.php';
require 'inc/session.php';
require 'inc/logs_core.php';
require 'inc/items_core.php';
if($_session->isLogged() == false)
	header('Location: index.php');
$_logs->set_session_obj($_session);

$_page = 6;

$role = $_session->get_user_role();
// Employees cannot have access to logs
if($role == 4)
	header('Location: items.php');

if(isset($_POST['act'])) {
	// Search count
	if($_POST['act'] == '1') {
		if(!isset($_POST['val']) || $_POST['val'] == '')
			die('wrong');
		$search_string = $_POST['val'];
		
		$itemid = $_POST['itemid'];
		$catid = $_POST['catid'];
		$userid = $_POST['userid'];
		
		if($itemid != 'no')
			$logs = $_logs->count_logs_search($search_string, $itemid, false, false);
		elseif($catid != 'no')
			$logs = $_logs->count_logs_search($search_string, false, $catid, false);
		elseif($userid != 'no')
			$logs = $_logs->count_logs_search($search_string, false, false, $userid);
		else
			$logs = $_logs->count_logs_search($search_string, false, false, false);
		
		if($logs == 0)
			die('2');
		die('3');
	}
}

if(!isset($_GET['page']) || $_GET['page'] == 0 || !is_numeric($_GET['page']))
	$page = 1;
else
	$page = $_GET['page'];

	
if(!isset($_GET['pp']) || !is_numeric($_GET['pp'])) {
	$pp = 25;
}else{
	$pp = $_GET['pp'];
	if($pp != 25 && $pp != 50 && $pp != 100 && $pp != 150 && $pp != 200 && $pp != 300 && $pp != 500)
		$pp = 25;
}

// Logs of an item, category or user id
if(isset($_GET['itemid']) && is_numeric($_GET['itemid'])){ 
	$itemid = $_GET['itemid'];
	$catid = false;
	$userid = false;
}elseif(isset($_GET['catid']) && is_numeric($_GET['catid'])){
	$catid = $_GET['catid'];
	$itemid = false;
	$userid = false;
}elseif(isset($_GET['userid']) && is_numeric($_GET['userid'])) {
	$userid = $_GET['userid'];
	$itemid = false;
	$catid = false;
}else{
	$catid = false;
	$itemid = false;
	$userid = false;
}

// Search query
if(isset($_GET['search']) && ($_GET['search'] != '')){
	$s = urldecode($_GET['search']);
	$logs = $_logs->search($s, $page, $pp, $itemid, $catid, $userid);
}else{
	$s = false;
	$logs = $_logs->get_logs($page, $pp, $itemid, $catid, $userid);
}


?>
<!DOCTYPE HTML>
<html>
<head>
<?php require 'inc/head.php'; ?>
</head>
<body>
	<div id="main-wrapper">
		<?php require 'inc/header.php'; ?>
		
		<div class="wrapper-pad">
			<?php
				if($itemid != false)
					echo "<h2>Logs - Producto:  $itemid</h2>";
				elseif($catid != false)
					echo "<h2>Logs - Categoría: $catid</h2>";
				elseif($userid != false)
					echo "<h2>Logs - Usuario: $userid</h2>";
				else
					echo '<h2>Logs</h2>';
			?>
			<div id="table-head">
				<form method="post" action="" name="searchf">
					<input type="text" name="search" placeholder="Buscar..." class="search fleft" <?php if($s!=false) echo 'value="'.$s.'"'; ?>/>
				</form>
				<img src="media/img/loader-small.gif" class="fleft loader" width="15" height="15" />
				<div class="fright">
					<div class="select-holder">
						<i class="fa fa-caret-down"></i>
						<select name="show-per-page">
							<option value="25" <?php if($pp==25) echo 'selected'; ?>>25</option>
							<option value="50" <?php if($pp==50) echo 'selected'; ?>>50</option>
							<option value="100" <?php if($pp==100) echo 'selected'; ?>>100</option>
							<option value="150" <?php if($pp==150) echo 'selected'; ?>>150</option>
							<option value="200" <?php if($pp==200) echo 'selected'; ?>>200</option>
							<option value="300" <?php if($pp==300) echo 'selected'; ?>>300</option>
							<option value="500" <?php if($pp==500) echo 'selected'; ?>>500</option>
						</select>
					</div>
				</div>
			</div>
			
			<?php
			if($s == false)
				$total_items = $_logs->count_logs($itemid, $catid, $userid);
			else
				$total_items = $_logs->count_logs_search($s, $itemid, $catid, $userid);
			if($total_items == 0)
				echo 'No se encontró registros<br /><br /><br />';
			else{
			?>
			
			<table border="1" rules="rows" id="logs">
				<thead>
					<tr>
						<td width="6%">ID</td>
						<td width="13%">Tipo</td>
						<td width="25%">Producto</td>
						<td width="12%">Desde</td>
						<td width="12%">Hasta</td>
						<td width="20%">Usuario</td>
						<td width="12%">Fecha</td>
					</tr>
				</thead>
				
				<tbody>
<?php

					while($log = $logs->fetch_object()) {
						if($log->type == 1){
							$type = 'Entrada';
							$from = $log->fromqty;
							$to = $log->toqty;
						}elseif($log->type == 2){
							$type = 'Salida';
							$from = $log->fromqty;
							$to = $log->toqty;
						}elseif($log->type == 3){
							$type = 'Precio';
							$from = $_logs->parse_price($log->fromprice);
							$to = $_logs->parse_price($log->toprice);
						}else{
							$type = '-';
							$from = '-';
							$to = '-';
						}
?>
					<tr data-id="<?php echo $log->id; ?>">
						<td><?php echo $log->id; ?></td>
						<td><?php echo $type; ?> </td>
						<td><?php echo $_items->get_item_name($log->item); ?></td>
						<td><?php echo $from; ?></td>
						<td><?php echo $to; ?></td>
						<td><?php echo $_session->get_user_name_by_id($log->user); ?></td>
						<td><?php echo date("d/m/Y", strtotime($log->date_added)); ?></td>
					</tr>
<?php
}
?>
				</tbody>
			</table>
		</div>
		
		<div id="pagination">
			<?php
			if($page != 1)
				echo '<div class="prev" name="'.($page-1).'"><i class="fa fa-caret-left"></i></div>';
			?>
			<div class="page"><?php echo $page; ?></div>
			<?php
			if($s == false)
				$total_items = $_logs->count_logs($itemid, $catid, $userid);
			else
				$total_items = $_logs->count_logs_search($s, $itemid, $catid, $userid);
			if($total_items > $pp) {
				$total_pages = $total_items / $pp;
				if($total_pages > $page)
					echo '<div class="next" name="'.($page+1).'"><i class="fa fa-caret-right"></i></div>';
			}
			?>
		</div>
		
		<div class="clear" style="margin-bottom:40px;"></div>
		<div class="border" style="margin-bottom:30px;"></div>
		
		<?php } ?>
	</div>
</body>
</html>