<?php
$headrole = $_session->get_user_role();
if($headrole == 1)
	$as = 'Administrador';
elseif($headrole == 2)
	$as = 'General Supervisor';
elseif($headrole == 3)
	$as = 'Supervisor';
elseif($headrole == 4)
	$as = 'Vendedor';
?>
<div id="header">
			<div class="left">
				<a href="http://webgratis.com.ar/" target='_blank'><img src="media/img/logo3x.png" width="150" height="50" alt="Inventario App" /></a>
				<div style="font-size:12px; font-style:italic;color:#bbb;"><?php echo $as; ?></div>
			</div>
			<div class="right">
				<?php
				if($headrole == 1 || $headrole == 2 || $headrole == 3)
					echo '<a href="users.php" title="Users">Usuarios</a>|';
				?>
				<a href="settings.php" title="Settings">Configuración</a>|
				<a href="logout.php" title="Logout">Salir</a>
			</div>
			<div class="clear"></div>
		</div>
		
		<input type="checkbox" class="toggle" id="opmenu" style="display:none"/>
		<label for="opmenu" id="open-menu"><i class="fa fa-align-justify"></i> Menu</label>
		<div id="menu">
			<ul id="menuli">
				<?php
				// Home only for Admin and General Supervisor (Stats)
				if($headrole == 1 || $headrole == 2) {
				?>
					<li<?php if($_page == 1) { ?> class="active"<?php } ?>><a href="home.php" title="Home"><i class="fa fa-home"></i> Inicio</a></li>
				<?php
				}
				?>
				
				<?php
				// Add Item only for Admin, General Supervisor and Supervisor
				if($headrole == 1 || $headrole == 2 || $headrole == 3){
				?>
					<li<?php if($_page == 2) { ?> class="active"<?php } ?>><a href="new-item.php" title="New Item"><i class="fa fa-plus"></i> Nuevo producto</a></li>
				<?php
				}
				?>
				
				<li<?php if($_page == 3) { ?> class="active"<?php } ?>><a href="items.php" title="Items"><i class="fa fa-list-ul"></i> Productos</a></li>
				<li<?php if($_page == 4) { ?> class="active"<?php } ?>><a href="check-in.php" title="Check-In Item"><i class="fa fa-arrow-down"></i> Ingresos</a></li>
				<li<?php if($_page == 5) { ?> class="active"<?php } ?>><a href="check-out.php" title="Check-Out Item"><i class="fa fa-arrow-up"></i> Salidas</a></li>
				
				<?php
				// Add Item only for Admin, General Supervisor and Supervisor
				if($headrole == 1 || $headrole == 2 || $headrole == 3){
				?>
					<li<?php if($_page == 6) { ?> class="active"<?php } ?>><a href="logs.php" title="Logs"><i class="fa fa-file-text-o"></i> Logs</a></li>
				<?php
				}
				?>
				<li<?php if($_page == 7) { ?> class="active"<?php } ?>><a href="categories.php" title="Categories"><i class="fa fa-folder"></i> Categorías</a></li>
			</ul>
		</div>