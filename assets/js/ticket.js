$(function () {
	$('#parts-form').submit(function(e) {
		e.preventDefault();
		console.log('Query is "' + $('#parts-form input[name="query"]').val() + '"');
		$.getJSON('api/defacto_parts.php?query=' + encodeURIComponent($('#parts-form input[name="query"]').val()), function(data) {
			$('#parts-list').empty().parent().hide();
			if ( data === undefined || data === null ) {
				$('#parts-list').append('<tr><td></td><td></td><td>No results found.</td></tr>');
			}
			$.each(data, function(key, val) {
				$('#parts-list').append('<tr class="clickable"><td>' + val.STRC_CODE + '</td><td>' + val.STRC_CODE2 + '</td><td>' + val.STRC_DESC + '</td><td><form class="add-part"><input type="hidden" name="ticket" value="' + $('#parts-form input[name="ticket"]').val() + '"><input type="hidden" name="STRC_CODE" value="' + val.STRC_CODE + '"><input type="hidden" name="STRC_CODE2" value="' + val.STRC_CODE2 + '"><input type="hidden" name="STRC_DESC" value="' + val.STRC_DESC + '"><input type="number" name="quantity" placeholder="Quantity" required>&emsp;<input type="submit" value="Add"></form></td></tr>');
			})
			$('table.tablesorter').trigger('update');
			$('#parts-list').parent().show();
		});
	});
	$('select[name="status"]').change(function() {
		if ( $('select[name="status"]').val() == 'Complete' ) {  
			$('.js-target-complete input').attr('required', true);
			$('.js-target-complete').show();
		} else {
			$('.js-target-complete input').removeAttr('required');
			$('.js-target-complete').hide();
		}
	});
});

$(document).on('submit', '.add-part', function(e) {
	e.preventDefault();
	console.log($(this).serialize());
	var addForm = $(this);
	$.getJSON('api/parts_add.php?' + $(this).serialize(), function(data) {
		console.log(data);
		if ( data.result ) {
			$('.no-parts-currently-added').remove();
			var STRC_CODE   = addForm.children('input[name="STRC_CODE"]' ).val();
			var STRC_CODE2  = addForm.children('input[name="STRC_CODE2"]').val();
			var description = addForm.children('input[name="STRC_DESC"]' ).val();
			var quantity    = addForm.children('input[name="quantity"]'  ).val();
			$('#current-parts').append('<tr class="clickable"><td>' + STRC_CODE + '</td><td>' + STRC_CODE2 + '</td><td>' + description + '</td><td>' + quantity + '</td><td><form class="remove-part"><input type="hidden" name="ticket" value="' + quantity + '"><input type="hidden" name="STRC_CODE" value="' + STRC_CODE + '"><input type="hidden" name="STRC_CODE2" value="' + STRC_CODE2 + '"><input type="hidden" name="STRC_DESC" value="' + description + '"><input type="submit" value="Remove"></form></td></tr>');
			$('table.tablesorter').trigger('update');
		} else {
			addForm.children('input[type=submit]').prop('disabled', true).css('color', '#c0392b');
		}
	});
});


$(document).on('submit', '.manual-part', function(e) {
	e.preventDefault();
	console.log($(this).serialize());
	var manualForm = $(this);
	$.getJSON('api/parts_manual.php?' + $(this).serialize(), function(data) {
		console.log(data);
		if ( data.result ) {
			$('.no-parts-currently-added').remove();
			var STRC_CODE   = 'MANUAL_ENTRY';
			var STRC_CODE2  = 'MANUAL_ENTRY';
			var description = manualForm.children('input[name="description"]').val();
			var quantity    = manualForm.children('input[name="quantity"]'   ).val();
			$('#current-parts').append('<tr class="clickable"><td>' + STRC_CODE + '</td><td>' + STRC_CODE2 + '</td><td>' + description + '</td><td>' + quantity + '</td><td><form class="remove-part"><input type="hidden" name="ticket" value="' + quantity + '"><input type="hidden" name="STRC_CODE" value="' + STRC_CODE + '"><input type="hidden" name="STRC_CODE2" value="' + STRC_CODE2 + '"><input type="hidden" name="STRC_DESC" value="' + description + '"><input type="submit" value="Remove"></form></td></tr>');
			$('table.tablesorter').trigger('update');
		} else {
			manualForm.children('input[type=submit]').prop('disabled', true).css('color', '#c0392b');
		}
	});
});

$(document).on('submit', '.update-part', function(e) {
	e.preventDefault();
	console.log($(this).serialize());
	var updateForm = $(this);
	$.getJSON('api/parts_update.php?' + $(this).serialize(), function(data) {
		console.log(data);
		if ( data.result ) {
			//updateForm.parent().parent().remove();
			updateForm.children('input[type=submit]').prop('disabled', true).css('color', '#27ae60');
			$('table.tablesorter').trigger('update');
		} else {
			updateForm.children('input[type=submit]').prop('disabled', true).css('color', '#c0392b');
		}
		setTimeout(function() {
			updateForm.children('input[type=submit]').prop('disabled', false).css('color', '');
		}, 3000);
	});
});

$(document).on('submit', '.remove-part', function(e) {
	e.preventDefault();
	console.log($(this).serialize());
	var removeForm = $(this);
	$.getJSON('api/parts_remove.php?' + $(this).serialize(), function(data) {
		console.log(data);
		if ( data.result ) {
			removeForm.parent().parent().remove();
			$('table.tablesorter').trigger('update');
		} else {
			removeForm.children('input[type=submit]').prop('disabled', true).css('color', '#c0392b');
		}
	});
});
