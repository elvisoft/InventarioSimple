<?php
require 'config.php';
require 'inc/session.php';
require 'inc/items_core.php';
if($_session->isLogged() == false)
	header('Location: index.php');
$_items->set_session_obj($_session);

$_page = 4;

if(isset($_POST['act'])) {
	// Search count
	if($_POST['act'] == '1') {
		if(!isset($_POST['val']) || $_POST['val'] == '')
			die('wrong');
		$search_string = $_POST['val'];
		if($_items->count_items_search($search_string) == 0)
			die('2');
		die('3');
	}
	
	if($_POST['act'] == '2') {
		if(!isset($_POST['id']) || !isset($_POST['val']) || !isset($_POST['fromval']) || $_POST['id'] == '' || $_POST['val'] == '' || $_POST['fromval'] == '')
			die('wrong');
		if($_items->update_item_qty(1, $_POST['id'], $_POST['fromval'], $_POST['val']) == true)
			die('1');
		die('wrong');
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

// Search query
if(isset($_GET['search']) && ($_GET['search'] != '')){
	$s = urldecode($_GET['search']);
	$items = $_items->search($s, $page, $pp);
	$c_items = $_items->count_items_search($s);
}else{
	$s = false;
	$items = $_items->get_items($page, $pp);
	$c_items = $_items->count_items();
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
			<h2 class="noborder">Entradas de productos</h2>
			<span class="downtitle">Clic en un elemento para ingresar productos...</span>
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
			if($c_items == 0)
				echo '<br /><br />No se encontró datos';
			else{
			?>
			
			<table border="1" rules="rows" id="items-check">
				<thead>
					<tr>
						<td width="7%">ID</td>
						<td width="40%">Producto</td>
						<td width="20%">Categoría</td>
						<td width="18%">Cantidad</td>
						<td width="15%">Precio</td>
					</tr>
				</thead>
				
				<tbody>
<?php
					while($item = $items->fetch_object()) {
?>
					<tr data-id="<?php echo $item->id; ?>">
						<td><?php echo $item->id; ?></td>
						<td><?php echo $item->name; ?></td>
						<td><?php echo $_items->get_category_name($item->category); ?></td>
						<td><?php echo $item->qty; ?></td>
						<td><?php echo $_items->parse_price($item->price); ?></td>
					</tr>
<?php
					}
?>
				</tbody>
			</table>
			<?php
			}
			?>
		</div>
		
		<div id="pagination">
			<?php
			if($page != 1)
				echo '<div class="prev" name="'.($page-1).'"><i class="fa fa-caret-left"></i></div>';
			?>
			<div class="page"><?php echo $page; ?></div>
			<?php
			if($s == false)
				$total_items = $_items->count_items();
			else
				$total_items = $_items->count_items_search($s);
			if($total_items > $pp) {
				$total_pages = $total_items / $pp;
				if($total_pages > $page)
					echo '<div class="next" name="'.($page+1).'"><i class="fa fa-caret-right"></i></div>';
			}
			?>
		</div>
		
		<div class="clear" style="margin-bottom:40px;"></div>
		<div class="border" style="margin-bottom:30px;"></div>
	</div>
</body>
</html>