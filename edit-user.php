<?php
require 'config.php';
require 'inc/session.php';
require 'inc/users_core.php';
if($_session->isLogged() == false)
	header('Location: index.php');

// User Role. Page only for Admins and General Supervisors (1/2)
$role = $_session->get_user_role();
if($role != 1 && $role != 2)
	header('Location: users.php');

$_page = 11;


if(isset($_POST['act'])) {
	if($_POST['act'] == '1') {
		if(!isset($_POST['userid']) || !isset($_POST['name']) || !isset($_POST['email']) || !isset($_POST['role']))
			die('wrong');
		if($_POST['userid'] == '' || $_POST['name'] == '' || $_POST['email'] == '' || $_POST['role'] == '')
			die('wrong');
		
		$userid = $_POST['userid'];
		$name = $_POST['name'];
		$email = $_POST['email'];
		$role = $_POST['role'];
		
		if($_users->userid_exists($userid) == false)
			return '2';
		
		if($role != 1 && $role != 2 && $role != 3 && $role != 4)
			die('wrong');
			
		$rgpx = "/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/"; 
		if(preg_match($rgpx, $email) === false)
			die('3');
		
		if($_users->edit_user($userid, $name, $email, $role) == true)
			die('1');
		die('wrong');
	}
}

if(!isset($_GET['userid']) || !is_numeric($_GET['userid']))
	header('Location: users.php');
$userid = $_GET['userid'];
$userd = $_users->get_user($userid);

if($_users->userid_exists($userid) == false)
	header('Location: users.php');
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
			<h2>Editar usuario</h2>
			<div class="center">
				<div class="new-item form">
					<form method="post" action="" name="edit-user" data-id="<?php echo $userd->id; ?>">
						Nombres:<br />
						<div class="ni-cont">
							<input type="text" name="euser-name" class="ni" value="<?php echo $userd->name; ?>" />
						</div>
						Email:<br />
						<div class="ni-cont">
							<input type="text" name="euser-email" class="ni" value="<?php echo $userd->email; ?>" />
						</div>
						Rol:<br />
						<div class="select-holder">
							<i class="fa fa-caret-down"></i>
							<select name="euser-role">
								<option value="1"<?php if($userd->role == 1) echo ' selected'; ?>>Administrador</option>
								<option value="2"<?php if($userd->role == 2) echo ' selected'; ?>>General Supervisor</option>
								<option value="3"<?php if($userd->role == 3) echo ' selected'; ?>>Supervisor</option>
								<option value="4"<?php if($userd->role == 4) echo ' selected'; ?>>Vendedor</option>
							</select>
						</div>
						<input type="submit" name="euser-submit" class="ni btn blue" value="Editar usuario" />
					</form>
				</div>
			</div>
		</div>
		
		<div class="clear" style="margin-bottom:40px;"></div>
		<div class="border" style="margin-bottom:30px;"></div>
	</div>
</body>
</html>