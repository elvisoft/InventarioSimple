<?php
require 'config.php';
require 'inc/session.php';
require 'inc/users_core.php';
if($_session->isLogged() == false)
	header('Location: index.php');
$_users->set_session_obj($_session);

$_page = 9;

$role = $_session->get_user_role();
if($role == 4)
	header('Location: home.php');

if(isset($_POST['act'])) {
	// Search count
	if($_POST['act'] == '1') {
		if(!isset($_POST['val']) || $_POST['val'] == '')
			die('wrong');
		$search_string = $_POST['val'];
		if($_users->count_users_search($search_string) == 0)
			die('2');
		die('3');
	}
	
	// Delete user
	if($_POST['act'] == '2') {
		if($_POST['id'] == '')
			die('wrong');

		// Check if actual user is the same id to prevent removal
		if($_session->get_user_id() == $_POST['id'])
			die('2');
		
		// Is the user an admin or general supervisor? Only admins can delete them
		$role = $_users->get_user($_POST['id']);
		$role = $role->role;
		if($role == 1 || $role == 2) {
			// Is the logged user an admin as well? Proceed
			if($_session->get_user_role() == 1) {
				if($_users->delete_user($_POST['id']) == true)
					die('1');
			}
			die('wrong');
		}elseif($role == 1 || $role == 2) {
			// Is the user a supervisor or employee? Admins and general supervisors can delete them
			if($_session->get_user_role() == 1 || $_session->get_user_role() == 2) {
				if($_users->delete_user($_POST['id']) == true)
					die('1');
			}
			die('wrong');
		}
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
	$users = $_users->search($s, $page, $pp);
}else{
	$s = false;
	$users = $_users->get_users($page, $pp);
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
			<?php
			if($s != false)
				echo '<h2>Usuarios - Buscar</h2>';
			else
				echo '<h2>Usuarios</h2>';
			?>
			<div id="table-head">
				<form method="post" action="" name="searchf">
					<input type="text" name="search" placeholder="Buscar..." class="search fleft" />
				</form>
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
				<a href="new-user.php" name="new-cat" class="btn blue fright"><i class="fa fa-plus"></i>Nuevo usuario</a>
			</div>
			
			<?php
			if($s == false)
				$total_items = $_users->count_users();
			else
				$total_items = $_users->count_users_search($s);
			if($total_items == 0)
				echo 'No items matched your query<br /><br /><br />';
			else{
			?>
			<table border="1" rules="rows" id="users">
				<thead>
					<tr>
						<td width="5%">ID</td>
						<td width="18%">Nombre</td>
						<td width="17%">Usuario</td>
						<td width="22%">Email</td>
						<td width="15%">Rol</td>
						<td width="14%">Registrado</td>
						<td width="9%">Acciones</td>
					</tr>
				</thead>
				
				<tbody>
<?php
					while($user = $users->fetch_object()) {
?>
					<tr data-type="element" data-id="<?php echo $user->id; ?>">
						<td class="hover" data-type="id"><?php echo $user->id; ?></td>
						<td class="hover" data-type="name"><?php echo $user->name; ?></td>
						<td class="hover" data-type="username"><?php echo $user->username; ?></td>
						<td class="hover" data-type="email"><?php echo $user->email; ?></td>
						<td class="hover" data-type="role"><?php echo $_users->parse_role($user->role); ?></td>
						<td class="hover" data-type="date"><?php echo $_users->parse_date($user->date_added); ?></td>
						<td>
							<?php
							if($role == 1 || $role == 2)
								echo '<a href="edit-user.php?userid='.$user->id.'" name="c3" title="Editar"><i class="fa fa-pencil"></i></a>';
							?>
							<a href="logs.php?userid=<?php echo $user->id; ?>" name="c4" title="Log del usuario"><i class="fa fa-file-text-o"></i></a>
							<?php
							if($role == 1 || $role == 2)
								echo '<a href="" name="c5" title="Eliminar"><i class="fa fa-close"></i></a>';
							?>
						</td>
					</tr>
<?php
}
}
?>
				</tbody>
			</table>
		</div>
		
		<div id="pagination">
			<?php
			if($page != 1)
				echo '<div class="prev" name="'.($page-1).'"><i class="fa fa-caret-left"></i></div>';
			?>
			<div class="page"><?php echo $page; ?></div>
			<?php
			if($s == false)
				$total_items = $_users->count_users();
			else
				$total_items = $_users->count_users_search($s);
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