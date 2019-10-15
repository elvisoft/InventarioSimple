$('document').ready(function() {
	$('form[name=new-user]').submit(function(evt) {
		evt.preventDefault();
		
		var name = $('input[name=nuser-name]').val();
		var username = $('input[name=nuser-user]').val();
		var password = $('input[name=nuser-pass]').val();
		var password2 = $('input[name=nuser-passr]').val();
		var email = $('input[name=nuser-email]').val();
		var role = $('select[name=nuser-role]').val();
		
		// Validate email
		var rgpx = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		
		// Empty Inputs
		if(name == '') {
			alert('Por favor, introduzca un nombre');
			return false;
		}else if(username == '') {
			alert('Por favor, introduzca un nombre de usuario');
			return false;
		}else if(password == '') {
			alert('Por favor, introduzca una contraseña');
			return false;
		}else if(password2 == '') {
			alert('Introduzca la confirmación de la contraseña');
			return false;
		}else if(email == '') {
			alert('Por favor, introduzca un correo electrónico');
			return false;
		}else if(password != password2) {
			alert('Las contraseñas no coinciden');
			return false;
		}else if(rgpx.test(email) == false) {
			alert('Introduce un correo electrónico válido');
			return false;
		}else if(password.length < 6){
			alert('La contraseña debe contener 6 caracteres como mínimo');
			return false;
		}
		
		
		$.post('new-user.php', {
			'act':'1',
			'name':name,
			'username':username,
			'password1':password,
			'password2':password2,
			'email':email,
			'role':role
		}, function(data) {
			if(data == '1') {
				alert('Usuario creado correctamente');
				location.href = 'new-user.php';
			}else if(data == '2') {
				alert('Las contraseñas no coinciden');
				return false;
			}else if(data == '3') {
				alert('Introduce un correo electrónico válido');
				return false;
			}else if(data == '4') {
				alert('La contraseña debe contener 6 caracteres como mínimo');
				return false;
			}else if(data == '5') {
				alert('nombre de usuario ya existe');
				return false;
			}else{
				alert('Algo salió mal. Por favor, vuelva a intentarlo');
				return false;
			}
		});
	});
});