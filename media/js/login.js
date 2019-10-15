$('document').ready(function() {
	$('form[name=login]').submit(function(evt) {
		evt.preventDefault();
		loader(true);
		
		var user = $(this).children('input[name=username]').val();
		var pass = $(this).children('input[name=password]').val();
		
		if(user == '') {
			error('Introduzca su nombre de usuario');
			loader(false);
		}else if(pass == '') {
			error('Por favor, introduzca la contraseña');
			loader(false);
		}else{
			loader(false);
			
			$.post('index.php', {
				'a':'1',
				'user':user,
				'pass':pass
			}, function(data) {
				if(data == '1') {
					error('Nombre de usuario o contraseña incorrectos');
				}else if(data == '2') {
					error('Algo salió mal. Póngase en contacto con el administrador');
				}else if(data == '3') {
					window.location = 'home.php';
				}else{
					error('Algo salió mal. Póngase en contacto con el administrador');
					alert(data);
				}
			});
		}
	});
	
	
	function loader(stat) {
		if(stat == true) {
			$('#loader').fadeIn(800);
		}else{
			$('#loader').fadeOut(300);
		}
		return true;
	}
	
	function error(msg) {
		if(msg == false) {
			$('#center').animate({'margin-bottom':'-170px'},500);
			$('#content').animate({'height':'280px'},500);
			$('#error').slideUp(500);
		}else{
			$('#center').animate({'margin-bottom':'-180px'},500);
			$('#content').animate({'height':'290px'},500);
			if($('#error').is(':visible')) {
				$('#error').slideUp(500, function() {
					$('#error').html(msg).slideDown(500);
				});
			}else{
				$('#error').html(msg).slideDown(500);
			}
		}
		return true;
	}
});