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
            var today = Date.now();  
			$('.js-target-complete').html('\
						<p><input type="checkbox" name="debris" id="complete-debris" required> <label for="complete-debris">Has all debris including redundant parts been cleared away and disposed of?</label></p>\
						<p><input type="checkbox" name="tools" id="complete-tools" required> <label for="complete-tools">Have all tools been accounted for?</label></p>\
						<p><input type="checkbox" name="parts" id="complete-parts" required> <label for="complete-parts">Have any parts found missing been reported?</label></p>\
						<p><input type="checkbox" name="equipment" id="complete-equipment" required> <label for="complete-equipment">Has all equipment used for access, such as ladders etc. been cleared and stored?</label></p>\
						<p><input type="date" name="time" id="complete-time" required value="' + today + '"> <label for="complete-time">If this job was completed before today, change the date here.</label></p>\
					');
		} else {
			$('.js-target-complete').empty();
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
			$('#current-parts').append('<tr class="clickable"><td>' + addForm.children('input[name="STRC_CODE"]').val() + '</td><td>' + addForm.children('input[name="STRC_CODE2"]').val() + '</td><td>' + addForm.children('input[name="STRC_DESC"]').val() + '</td><td>' + addForm.children('input[name="quantity"]').val() + '</td><td><form class="remove-part"><input type="hidden" name="ticket" value="' + addForm.children('input[name="quantity"]').val() + '"><input type="hidden" name="STRC_CODE" value="' + addForm.children('input[name="STRC_CODE"]').val() + '"><input type="hidden" name="STRC_CODE2" value="' + addForm.children('input[name="STRC_CODE2"]').val() + '"><input type="hidden" name="STRC_DESC" value="' + addForm.children('input[name="STRC_DESC"]').val() + '"><input type="submit" value="Remove"></form></td></tr>');
			$('table.tablesorter').trigger('update');
		} else {
			addForm.children('input[type=submit]').prop('disabled', true).css('color', '#c0392b');
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
