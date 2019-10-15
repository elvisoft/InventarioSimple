$('document').ready(function() {
	$('form[name=edit-cat]').submit(function(evt) {
		evt.preventDefault();
		
		var catid = $(this).data('id');
		var name = $('input[name=ncat-name]').val();
		var place = $('input[name=ncat-place]').val();
		var desc = $('textarea[name=ncat-descrp]').val();
		
		if(name == '') {
			alert('Por favor, introduzca un nombre de categoría');
			return false;
		}
		
		$.post('edit-category.php', {
			'act':'1',
			'catid':catid,
			'name':name,
			'place':place,
			'desc':desc
		}, function(data) {
			if(data == '1') {
				alert('Categoría actualizada con éxito');
				location.href = 'categories.php';
			}else{
				alert('Algo salió mal. Por favor, vuelva a intentarlo');
				return false;
			}
		});
	});
	
	$('textarea[name=ncat-descrp]').keyup(function(evt) {
		var count = $(this).val().length;
		var limit = 400;
		var val = $(this).val();
		var t = $(this);
		
		if(count > limit){
			t.val(val.substr(0,400));
			var dif = 0;
		}else
			var dif = limit-count;
		$('span.ncat-desc-left').html('Descripción de categoria ('+dif+' caracteres restantes):');
	});
});