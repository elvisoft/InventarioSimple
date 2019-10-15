$('document').ready(function() {
	$('form[name=account-settings]').submit(function(evt) {
		evt.preventDefault();
		
		var name = $('input[name=name]').val();
		var email = $('input[name=email]').val();
		
		// Validate email
		if(email != undefined) {
			var rgpx = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		}
		
		// Empty Inputs
		if(name != undefined && name == '') {
			alert('Por favor, introduzca un nombre');
			return false;
		}
		if(email != undefined && email == '') {
			alert('Por favor, introduzca un correo electrónico');
			return false;
		}
		if(email != undefined && rgpx.test(email) == false) {
			alert('Introduce un correo electrónico válido');
			return false;
		}
		
		if(name == undefined)
			name = 'false';
		if(email == undefined)
			email = 'false';
		
		$.post('settings.php', {
			'act':'1',
			'name':name,
			'email':email
		},function(data) {
			if(data == '1') {
				alert('Cambios guardados correctamente');
				location.href = 'settings.php';
			}else if(data == '2') {
				alert('Introduce un correo electrónico válido');
				return false;
			}else{
				alert('Algo salió mal. Por favor, inténtelo de nuevo');
				return false;
			}
		});
	});
	
	
	$('form[name=change-password]').submit(function(evt) {
		evt.preventDefault();
		
		var pass1 = $('input[name=new-password]').val();
		var pass2 = $('input[name=rnew-password]').val();
		
		if(pass1 == '') {
			alert('Por favor, introduzca una contraseña');
			return false;
		}else if(pass2 == '') {
			alert('Introduzca la confirmación de la contraseña');
			return false;
		}else if(pass1 != pass2) {
			alert('Las contraseñas no coinciden');
			return false;
		}else if(pass1.length < 6){
			alert('La contraseña debe contener 6 caracteres como mínimo');
			return false;
		}
		
		$.post('settings.php', {
			'act':'2',
			'password1':pass1,
			'password2':pass2
		},function(data) {
			alert(data);
			if(data == '1') {
				alert('Contraseña cambiada correctamente');
				location.href = 'settings.php';
			}else if(data == '2') {
				alert('Las contraseñas no coinciden');
				return false;
			}else{
				alert('Algo salió mal. Por favor, inténtelo de nuevo');
				return false;
			}
		});
	});
	
	
	$('form[name=invento-settings]').submit(function(evt) {
		evt.preventDefault();
		
		var ch1 = $('input[name=allow-namechange]').prop('checked');
		var ch2 = $('input[name=allow-emailchange]').prop('checked');

		ch1 = (ch1 == true) ? 'y' : 'n';
		ch2 = (ch2 == true) ? 'y' : 'n';
		
		$.post('settings.php', {
			'act':'3',
			'namechange':ch1,
			'emailchange':ch2
		},function(data) {
			if(data == '1') {
				alert('Configuración cambiada correctamente');
				location.href = 'settings.php';
			}else{
				alert('Algo salió mal. Por favor, inténtelo de nuevo');
				return false;
			}
		});
	});
});