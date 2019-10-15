<?php
require 'config.php';
require 'inc/session.php';
require 'inc/categories_core.php';
require 'inc/items_core.php';
if($_session->isLogged() == false)
	header('Location: index.php');

$_page = 7;

$role = $_session->get_user_role();

if(isset($_POST['act'])) {
	// Search count
	if($_POST['act'] == '1') {
		if(!isset($_POST['val']) || $_POST['val'] == '')
			die('wrong');
		$search_string = $_POST['val'];
		if($_cats->count_cats_search($search_string) == 0)
			die('2');
		die('3');
	}
	
	// Delete Category
	if($_POST['act'] == '2') {
		if(!isset($_POST['id']) || $_POST['id'] == '')
			die('wrong');
		if($_cats->delete_cat($_POST['id']) == true)
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
	$cats = $_cats->search($s, $page, $pp);
	$c_cats = $_cats->count_cats_search($s);
}else{
	$s = false;
	$cats = $_cats->get_cats($page, $pp);
	$c_cats = $_cats->count_cats();
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
			<h2>Categorías</h2>
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
				<div class="fright" style="height:5px; margin-right:55px;"></div>
				<?php
				if($role == 1 || $role == 2)
					echo '<a href="new-category.php" name="new-cat" class="btn blue fright"><i class="fa fa-plus"></i>Nueva categoría</a>';
				?>
			</div>
			
			<?php
			if($c_cats == 0)
				echo '<br /><br />No se encontró datos';
			else{
			?>
			
			<table border="1" rules="rows" id="categories" >
				<thead>
					<tr>
						<td width="5%"><input type="checkbox" name="select-all" /></td>
						<td width="6%">ID</td>
						<td width="28%">Nombre de la categoría</td>
						<td width="19%">Lugar</td>
						<td width="14%">Productos vinculados</td>
						<td width="17%">Total de productos</td>
						<td width="11%">Acciones</td>
					</tr>
				</thead>
				<tbody>
<?php
					while($cat = $cats->fetch_object()) {
?>
					<tr data-type="element" data-id="<?php echo $cat->id;?>">
						<td><input type="checkbox" name="chbox" /></td>
						<td class="hover" data-type="id"><?php echo $cat->id; ?></td>
						<td class="hover" data-type="name"><?php echo $cat->name; ?></td>
						<td><?php echo $cat->place; ?></td>
						<td><?php echo $_items->get_cat_reg_items($cat->id); ?></td>
						<td><?php echo $_items->get_cat_tot_items($cat->id); ?></td>
						<td>
							<?php
							if($role == 1 || $role == 2)
								echo '<a href="edit-category.php?id='.$cat->id.'" name="c3" title="Edit Item"><i class="fa fa-pencil"></i></a>';
							if($role == 1 || $role == 2 || $role == 3)
								echo '<a href="logs.php?catid='.$cat->id.'" name="c4" title="Log of this category"><i class="fa fa-file-text-o"></i></a>';
							if($role == 1 || $role == 2)
								echo '<a href="" name="c5" title="Delete Item"><i class="fa fa-close"></i></a>';
							?>
						</td>
					</tr>
<?php
}
?>
				</tbody>
			</table>
			<?php } ?>
		</div>
		
		<div id="pagination">
			<?php
			if($page != 1)
				echo '<div class="prev" name="'.($page-1).'"><i class="fa fa-caret-left"></i></div>';
			?>
			<div class="page"><?php echo $page; ?></div>
			<?php
			if($s == false)
				$total_items = $_cats->count_cats();
			else
				$total_items = $_cats->count_cats_search($s);
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