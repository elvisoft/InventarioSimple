<?php
$_titles = array('','Inicio','Nuevo producto','Productos','Ingresos','Salidas','Logs','Categorías','Nueva categoría','Usuarios','Nuevo usuario','Editar usuario','Configuración','Editar producto','Editar categoría','Detalle de categoría','Detalle del producto','Detalle del usuario');
$_title = $_titles[$_page];
?>
	<meta charset="utf-8" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no" />
	<title>Inventario App Simple - <?php echo $_title; ?></title>
	
<?php if($_page == 1) { ?>
	<link type="text/css" rel="stylesheet" href="media/css/home.css" media="all" />
<?php }else{ ?>
	<link type="text/css" rel="stylesheet" href="media/css/site.css" media="all" />
	<link type="text/css" rel="stylesheet" href="media/css/site-forms.css" media="all" />
	<link type="text/css" rel="stylesheet" href="media/css/site-responsive.css" media="all" />
<?php } ?>
	<link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,300italic,400,600" rel="stylesheet" type="text/css">
	<link rel="icon" href="media/img/favicon.ico" type="image/x-icon" />
	
	<script type="text/javascript" src="media/js/jquery.min.js"></script>
	
<?php
switch($_page) {
	case 1: echo '	<script type="text/javascript" src="media/js/home.js"></script>'; break;
	case 2: echo '	<script type="text/javascript" src="media/js/new-item.js"></script>'; break;
	case 3: echo '	<script type="text/javascript" src="media/js/items.js"></script>'; break;
	case 4:
		echo '	<script type="text/javascript" src="media/js/check.js"></script>';
		echo '	<script type="text/javascript">setPage("in");</script>'; break;
	case 5:
		echo '	<script type="text/javascript" src="media/js/check.js"></script>';
		echo '	<script type="text/javascript">setPage("out");</script>'; break;
	case 6: echo '	<script type="text/javascript" src="media/js/logs.js"></script>'; break;
	case 7: echo '	<script type="text/javascript" src="media/js/cats.js"></script>'; break;
	case 8: echo '	<script type="text/javascript" src="media/js/new-cat.js"></script>'; break;
	case 9: echo '	<script type="text/javascript" src="media/js/users.js"></script>'; break;
	case 10: echo '	<script type="text/javascript" src="media/js/new-user.js"></script>'; break;
	case 11: echo '	<script type="text/javascript" src="media/js/edit-user.js"></script>'; break;
	case 12: echo '	<script type="text/javascript" src="media/js/settings.js"></script>'; break;
	case 13: echo '	<script type="text/javascript" src="media/js/edit-item.js"></script>'; break;
	case 14: echo '	<script type="text/javascript" src="media/js/edit-cat.js"></script>'; break;
} ?>