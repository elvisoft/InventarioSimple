<?php
require 'config.php';
require 'inc/session.php';
require 'inc/items_core.php';
require 'inc/categories_core.php';
if($_session->isLogged() == false)
	header('Location: index.php');

$_page = 16;

if(!isset($_GET['id']))
	header('Location: items.php');
$item = $_items->get_item($_GET['id']);
if(!$item->id)
	header('Location: items.php');
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
			<h2>Item Details</h2>
			<div class="center">
				<div class="form">
					Item ID:<br />
					<div class="ni-cont light">
						<?php echo $item->id; ?><br /><br />
					</div>
					Item Name:<br />
					<div class="ni-cont light">
						<?php echo $item->name; ?><br /><br />
					</div>
					Item Description:<br />
					<div class="ni-cont light">
						<?php echo $item->descrp; ?><br /><br />
					</div>
					Item Category:<br />
					<div class="ni-cont light">
						<?php
						$cat = $_cats->get_cat($item->category);
						echo $cat->name;
						?><br /><br />
					</div>
					Item Quantity:<br />
					<div class="ni-cont light">
						<?php echo $item->qty; ?><br /><br />
					</div>
					Price of each item:<br />
					<div class="ni-cont light">
						<?php echo '$'.$item->price; ?>
					</div>
				</div>
			</div>
		</div>
		
		<div class="clear" style="margin-bottom:40px;"></div>
		<div class="border" style="margin-bottom:30px;"></div>
	</div>
</body>
</html>