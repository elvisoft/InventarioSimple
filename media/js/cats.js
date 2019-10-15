$('document').ready(function() {
	// Delete Item
	$('a[name=c5]').click(function(evt) {
		evt.preventDefault();
		var t = $(this);
		
		if(confirm('¿Seguro que quieres eliminar esta categoría? Todos los artículos y los registros en esta categoría serán suprimidos también') == true) {
			var id = t.parent().parent().data('id');
			var element = t.parent().parent();
			var height = element.height();
			
			$.post('categories.php', {
				'act':'2',
				'id':id
			}, function(data) {
				if(data == '1')
					element.fadeOut(700);
				else
					alert('Algo salió mal. Por favor, inténtelo de nuevo');
			});
		}
	});
	
	// Search
	$('form[name=searchf]').submit(function(evt) {
		evt.preventDefault();
		$('.loader').fadeIn(200);
		var val = $(this).children('input[name=search]').val();
		
		if(val == '') {
			$('.loader').fadeOut(200);
			alert('Por favor escriba su búsqueda');
			return false;
		}
		
		$.post('categories.php', {
			'act':'1',
			'val':val
		}, function(data) {
			if(data == '3') {
				$('.loader').fadeOut(200);
				location.href = 'categories.php?search='+encodeURIComponent(val);
				return false;
			}
			if(data == '2') {
				$('.loader').fadeOut(200);
				alert('No hay elementos que coincidan con su búsqueda');
				return false;
			}
			
			$('.loader').fadeOut(200);
			alert('Algo salió mal. Por favor, inténtelo de nuevo');
			return false;
		});
	});
	
	$('tr[data-type=element] td[data-type=id], tr[data-type=element] td[data-type=name]').click(function() {
		var cat = $(this).parent().data('id');
		location.href = 'cat.php?id='+cat;
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

		location.href = 'categories.php?'+url;
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