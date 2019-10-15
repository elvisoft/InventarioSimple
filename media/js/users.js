$('document').ready(function() {
	// Delete user
	$('a[name=c5]').click(function(evt) {
		evt.preventDefault();

		var id = $(this).parent().parent().data('id');
		var element = $(this).parent().parent();
		
		$.post('users.php', {
			'act':'2',
			'id':id
		}, function(data) {
			if(data == '1')
				element.fadeOut(700);
			else if(data == '2')
				alert('No puedes eliminar tu propio usuario');
			else
				alert('Algo salió mal. Por favor, inténtelo de nuevo');
			return false;
		});
	});
	
	// Search
	$('form[name=searchf]').submit(function(evt) {
		evt.preventDefault();
		$('.loader').fadeIn(200);
		var val = $(this).children('input[name=search]').val();
		
		if(val == '') {
			alert('Por favor escriba su búsqueda');
			$('.loader').fadeOut(200);
			return false;
		}
		
		$.post('users.php', {
			'act':'1',
			'val':val
		}, function(data) {
			if(data == '3') {
				location.href = 'users.php?search='+encodeURIComponent(val);
				$('.loader').fadeOut(200);
				return false;
			}
			if(data == '2') {
				alert('No hay elementos que coincidan con su búsqueda');
				$('.loader').fadeOut(200);
				return false;
			}
			
			alert('Algo salió mal. Por favor, inténtelo de nuevo');
		
			$('.loader').fadeOut(200);
			return false;
		});
	});
	
	$('tr[data-type=element] td[data-type=id], tr[data-type=element] td[data-type=name], tr[data-type=element] td[data-type=username], tr[data-type=element] td[data-type=email], tr[data-type=element] td[data-type=role], tr[data-type=element] td[data-type=date]').click(function() {
		var user = $(this).parent().data('id');
		location.href = 'user.php?id='+user;
	});
	
	// Previous Page
	$('#pagination .prev').click(function() { go('prev', $(this).attr('name')); });
	
	// Next page
	$('#pagination .next').click(function() { go('next', $(this).attr('name')); });
	
	// Show per page
	$('select[name=show-per-page]').on('change', function() { go('show-per-page', this.value); });
	
	// Handler of pagination and show per page
	function go(act, val) {
		var search = urlGet()['search'];
		if(act == 'prev' || act == 'next') {
			var p = val;
			var pp = urlGet()['pp'];
			var url = 'page='+p;

			if(pp != undefined) url = url+'&pp='+pp;
		}else if(act == 'show-per-page') {
			var pp = val;
			var page = urlGet()['page'];
			var url = 'pp='+pp;
			
			if(page != undefined) url = url+'&page='+page;
		}
		
		if(search != undefined) url = url+'&search='+search;

		location.href = 'users.php?'+url;
	}
	
	// Get url GET params
	function urlGet() {
		var vars = {};
		var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
			vars[key] = value;
		});
		return vars;
	}
});