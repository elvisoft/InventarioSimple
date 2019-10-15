<?php
require 'config.php';
require 'inc/session.php';
require 'inc/items_core.php';
require 'inc/categories_core.php';
if($_session->isLogged() == false)
	header('Location: index.php');
$_items->set_session_obj($_session);

$_page = 13;

$role = $_session->get_user_role();
if($role != 1 && $role != 2)
	header('Location: items.php');

if(isset($_POST['act'])) {
	if($_POST['act'] == '1') {
		if(!isset($_POST['itemid']) || !isset($_POST['name']) || !isset($_POST['desc']) || !isset($_POST['cat']) || !isset($_POST['price']))
			die('wrong');
		if($_POST['itemid'] == '' || $_POST['name'] == '' || $_POST['cat'] == '' || $_POST['price'] == '')
			die('wrong');
		
		$itemid = $_POST['itemid'];
		$name = $_POST['name'];
		$desc = $_POST['desc'];
		$cat = $_POST['cat'];
		$price = $_POST['price'];
		
		// Fix price
		$price = (string)$price;
		if(strpos($price, '.') === false) {
			$price = $price.'.00';
		}else{
			$pos = strpos($price, '.');
			if($price{$pos+1} == null)
				$price = $price.'00';
			elseif(!isset($price{$pos+2}))
				$price = $price.'0';
			else
				$price = substr($price,0,$pos+3);
		}
		
		if(substr_count($price, '.') > 1)
			die('wrong');
		
		if($_items->update_item($itemid, $name, $desc, $cat, $price) == false)
			die('wrong');
		die('1');
	}
}

if(!isset($_GET['id']))
	header('Location: items.php');
$itemid = $_GET['id'];

if($_items->get_item_name($itemid) == '')
	header('Location: items.php');

$item = $_items->get_item($itemid);
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
			<h2>Editar producto (ID <?php echo $itemid; ?>)</h2>
			<div class="center">
				<div class="new-item form">
					<form method="post" action="" name="edit-item" data-id="<?php echo $itemid; ?>">
						Nombre:<br />
						<div class="ni-cont">
							<input type="text" name="item-name" class="ni" value="<?php echo $item->name; ?>" />
						</div>
						<?php
						$desc = 400 - strlen($item->descrp);
						?>
						<span class="item-desc-left">Descripción (<?php echo $desc; ?> caracteres restantes):</span><br />
						<div class="ni-cont">
							<textarea name="item-descrp" class="ni"><?php echo $item->descrp; ?></textarea>
						</div>
						Categoría:<br />
						<div class="select-holder">
							<i class="fa fa-caret-down"></i>
							<?php
							if($_cats->count_cats() == 0)
								echo '<select name="item-category" disabled><option val="no">You need to create a category first</option></select>';
							else{
								echo '<select name="item-category">';
								$cats = $_cats->get_cats_dropdown();
								while($catt = $cats->fetch_object()) {
									echo "<option value=\"{$catt->id}\">{$catt->name}</option>";
								}
								echo '</select>';
							}
							?>
						</div>
						Precio:<br />
						<input type="text" name="item-price" class="ni-small" placeholder="0.00" value="<?php echo $item->price; ?>" />
						<input type="submit" name="item-submit" class="ni btn blue" value="Guardar datos" />
					</form>
				</div>
			</div>
		</div>
		
		<div class="clear" style="margin-bottom:40px;"></div>
		<div class="border" style="margin-bottom:30px;"></div>
	</div>
</body>
</html>