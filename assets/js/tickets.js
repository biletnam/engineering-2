$(function () {
	$('select[name="department"]').change(function() {
		console.log('Department Changed to "' + $(this).val() + '"');
		if ( $(this).val() == '*' ) {
			$('select[name="line"]')
				.empty()
				.append('<option value="*" disabled selected>All Lines</option>');
			$('select[name="machine"]')
				.empty()
				.append('<option value="*" disabled selected>All Machines</option>');
		} else {
			$.getJSON('api/list_lines.php?department=' + encodeURIComponent($(this).val()) , function( data ) {
				$('select[name="line"]')
					.empty()
					.append('<option value="*" selected>All Lines</option>');
				$.each( data, function( key, val ) {
					$('select[name="line"]').append('<option value="' + val + '">' + val + '</option>');
				});
			});
		}
	});
	$('select[name="line"]').change(function() {
		console.log('Line Changed to "' + $(this).val() + '"');
		if ( $(this).val() == '*' ) {
			$('select[name="machine"]')
				.empty()
				.append('<option value="*" disabled selected>All Machines</option>');
		} else {
			$.getJSON('api/list_machines.php?department=' + encodeURIComponent($('select[name="department"]').val()) + '&line=' + encodeURIComponent($(this).val()) , function( data ) {
				$('select[name="machine"]')
					.empty()
					.append('<option value="*" selected>All Machines</option>');
				$.each( data, function( key, val ) {
					$('select[name="machine"]').append('<option value="' + key + '">' + val + '</option>');
				});
			});
		}
	});
});
