$('document').ready(function() {
	$('form[name=edit-user]').submit(function(evt) {
		evt.preventDefault();
		
		var userid = $(this).data('id');
		var name = $('input[name=euser-name]').val();
		var email = $('input[name=euser-email]').val();
		var role = $('select[name=euser-role]').val();
		
		// Validate email
		var rgpx = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		
		// Empty Inputs
		if(name == '') {
			alert('Por favor, introduzca un nombre');
			return false;
		}else if(email == '') {
			alert('Por favor, introduzca un correo electrónico');
			return false;
		}else if(rgpx.test(email) == false) {
			alert('Introduce un correo electrónico válido');
			return false;
		}
		
		
		$.post('edit-user.php', {
			'act':'1',
			'userid':userid,
			'name':name,
			'email':email,
			'role':role
		}, function(data) {
			alert(data);
			if(data == '1') {
				alert('Usuario actualizado correctamente');
				location.href = 'users.php';
			}else if(data == '2') {
				alert('El usuario no existe');
				return false;
			}else if(data == '3') {
				alert('Introduce un correo electrónico válido');
				return false;
			}else{
				alert('Algo salió mal. Por favor, vuelva a intentarlo');
				return false;
			}
		});
	});
});