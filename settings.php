<?php
require 'config.php';
require 'inc/session.php';
require 'inc/users_core.php';
require 'inc/settings_core.php';
if($_session->isLogged() == false)
	header('Location: index.php');

$_page = 12;
$userid = $_session->get_user_id();

$role = $_session->get_user_role();
$sitedata = array(
	$_settings->get_setting('allow_namechange'),
	$_settings->get_setting('allow_emailchange')
);

if(isset($_POST['act'])) {
	// Account Settings
	if($_POST['act'] == '1') {
		if(!isset($_POST['name']) || !isset($_POST['email']))
			die('wrong');
		if($_POST['name'] == '' || $_POST['email'] == '')
			die('wrong');
			
		$name = $_POST['name'];
		$email = $_POST['email'];
		
		// If we are not an admin
		if($role != 1) {
			if($sitedata[0] == 'y' && $sitedata[1] == 'y') {
				$rgpx = "/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/"; 
				if(preg_match($rgpx, $email) === false)
					die('2');
				$_users->update_user($userid,$name,$email);
				die('1');
			}elseif($sitedata[0] == 'y' && $sitedata[1] == 'n') {
				$_users->update_user($userid,$name,false);
				die('1');
			}elseif($sitedata[0] == 'n' && $sitedata[1] == 'y') {
				$rgpx = "/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/"; 
				if(preg_match($rgpx, $email) === false)
					die('2');
				$_users->update_user($userid,false,$email);
				die('1');
			}else{
				die('wrong');
			}
		}
		
		$rgpx = "/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/"; 
		if(preg_match($rgpx, $email) === false)
			die('2');
		
		if($_users->update_user($userid,$name,$email) == true)
			die('1');
		die('wrong');
	}
	
	// Change Password
	if($_POST['act'] == '2') {
		if(!isset($_POST['password1']) || !isset($_POST['password2']))
			die('wrong');
		if($_POST['password1'] == '' || $_POST['password2'] == '')
			die('wrong');
		
		$password1 = $_POST['password1'];
		$password2 = $_POST['password2'];
		if($password1 != $password2)
			die('2');
		
		if($_users->update_pass($userid, $password1) == true) {
			if($_session->refresh_password($password1) == true)
				die('1');
		}
		die('wrong');
	}
	
	// Invento Settings
	if($_POST['act'] == '3') {
		if($role != 1)
			die('wrong');
			
		if(!isset($_POST['namechange']) || !isset($_POST['emailchange']))
			die('wrong');
		if($_POST['namechange'] == '' || $_POST['emailchange'] == '')
			die('wrong');
		
		$nc = $_POST['namechange'];
		$ec = $_POST['emailchange'];
		if(($nc != 'y' && $nc != 'n') || ($nc != 'y' && $nc != 'n'))
			die('wrong');
		
		$_settings->update_setting('allow_namechange', $nc);
		$_settings->update_setting('allow_emailchange', $ec);
		die('1');
	}
}

$userdata = $_users->get_user($userid);
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
			if($sitedata[0] == 'y' || $sitedata[1] == 'y' || $role == 1) {
			?>
			<h2>Configuraciones de la cuenta</h2>
			<div class="center">
				<div class="form">
					<form method="post" action="" name="account-settings">
						<?php
						if($sitedata[0] == 'y' || $role == 1) {
						?>
						Nombre:<br />
						<div class="ni-cont">
							<input type="text" name="name" class="ni" value="<?php echo $userdata->name; ?>" />
						</div>
						<?php } ?>
						
						<?php
						if($sitedata[1] == 'y' || $role == 1) {
						?>
						Email:<br />
						<div class="ni-cont">
							<input type="text" name="email" class="ni" value="<?php echo $userdata->email; ?>" />
						</div>
						<?php
						}
						?>
						<br />
						<input type="submit" name="invento-settings-savesettings" class="ni btn blue" value="Guardar configuración" />
					</form>
				</div>
			<div>
			<?php } ?>
			
			<h2 class="noborder">Cambiar contraseña</h2>
			<span class="downtitle">Si no deseas cambiar su contraseña, deja los siguientes cuadros vacíos.</span>
			<div class="center">
				<div class="form">
					<form method="post" action="" name="change-password">
						Nueva contraseña:<br />
						<div class="ni-cont">
							<input type="password" name="new-password" class="ni" />
						</div>
						
						Repetir contraseña:<br />
						<div class="ni-cont">
							<input type="password" name="rnew-password" class="ni" />
						</div>
						<br />
						<input type="submit" name="invento-settings-changepass" class="ni btn blue" value="Resetear contraseña" />
					</form>
				</div>
			</div>
			
			<?php
			if($role == 1) {
			?>
			<h2>Configuración del inventario</h2>
			<div class="center">
				<div class="form">
					<form method="post" action="" name="invento-settings">
						<div class="checkbox"><input type="checkbox" name="allow-namechange" value="y" <?php if($sitedata[0] == 'y') echo 'checked'; ?>/>Permitir a los usuarios cambiar su nombre<br /></div>
						<div class="checkbox"><input type="checkbox" name="allow-emailchange" value="y" <?php if($sitedata[1] == 'y') echo 'checked'; ?>/>Permitir que los usuarios cambien su correo electrónico<br /></div>
						<br />
						<input type="submit" name="invento-settings-save" class="ni btn blue" value="Guardar datos" />
					</form>
				</div>
			</div>
			<?php } ?>
		</div>
		
		<div class="clear" style="margin-bottom:40px;"></div>
		<div class="border" style="margin-bottom:30px;"></div>
	</div>
</body>
</html>