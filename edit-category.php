<?php
require 'config.php';
require 'inc/session.php';
require 'inc/categories_core.php';
if($_session->isLogged() == false)
	header('Location: index.php');

$_page = 14;

$role = $_session->get_user_role();
// Only Admin and General Supervisor can edit categories
if($role != 1 && $role != 2)
	header('Location: categories.php');

if(isset($_POST['act'])) {
	if($_POST['act'] == '1') {
		if(!isset($_POST['catid']) || !isset($_POST['name']) || !isset($_POST['place']) || !isset($_POST['desc']))
			die('wrong');
		if($_POST['catid'] == '' || $_POST['name'] == '')
			die('wrong');
		
		$catid = $_POST['catid'];
		$name = $_POST['name'];
		$place = $_POST['place'];
		$desc = $_POST['desc'];
		
		if($_cats->edit_cat($catid, $name, $place, $desc) == true)
			die('1');
		die('wrong');
	}
}

if(!isset($_GET['id']))
	header('Location: categories.php');
$cat = $_cats->get_cat($_GET['id']);
if(!$cat->id)
	header('Location: categories.php');
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
			<h2>Editar categoría</h2>
			<div class="center">
				<div class="form">
					<form method="post" action="" name="edit-cat" data-id="<?php echo $cat->id; ?>">
						Nombre de categoría:<br />
						<div class="ni-cont">
							<input type="text" name="ncat-name" class="ni" value="<?php echo $cat->name; ?>" />
						</div>
						Lugar de categoría:<br />
						<div class="ni-cont">
							<input type="text" name="ncat-place" class="ni" value="<?php echo $cat->place; ?>" />
						</div>
						<span class="ncat-desc-left">Descripción de categoría (<?php echo 400-strlen($cat->descrp); ?> caracteres restantes):</span><br />
						<div class="ni-cont">
							<textarea name="ncat-descrp" class="ni"><?php echo $cat->descrp; ?></textarea>
						</div>
						<input type="submit" name="ncat-submit" class="ni btn blue" value="Guardar datos" />
					</form>
				</div>
			</div>
		</div>
		
		<div class="clear" style="margin-bottom:40px;"></div>
		<div class="border" style="margin-bottom:30px;"></div>
	</div>
</body>
</html>