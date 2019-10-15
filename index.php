<?php
require 'config.php';
require 'inc/session.php';

if(isset($_POST['a']) && isset($_POST['user']) && isset($_POST['pass'])) {
	$user = $_POST['user'];
	$pass = $_POST['pass'];
	if($user == '' || $pass == '') die('2');
	
	if($_session->login($user, $pass) == false)
		die('1');
	die('3');
}

if($_session->isLogged())
	header('Location: home.php');
?>
<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no" />
	<title>Inventario - Login</title>
	
	<link type="text/css" rel="stylesheet" href="media/css/login.css" media="all" />
	<link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,300italic,400,600" rel="stylesheet" type="text/css">
	<link rel="icon" href="media/img/favicon.ico" type="image/x-icon" />
	
	<script type="text/javascript" src="media/js/jquery.min.js"></script>
	<script type="text/javascript" src="media/js/login.js"></script>
</head>
<body>
	<div id="center"></div>
	
	<div id="content">
		<div id="logo">
			<img src="media/img/logo3x-login.png" width="225" height="75" alt="Invento" />
		</div>
		
		<div id="login">
			<div id="error"></div>
			<form method="POST" action="" name="login">
				USUARIO:<br />
				<input type="text" name="username" /><br />
				CONTRASEÑA:<br />
				<input type="password" name="password" /><br />
				
				<img src="media/img/loader.gif" id="loader">
				<input type="submit" name="send" value="Ingresar" />
			</form>
		</div>
	</div>
</body>
</html>