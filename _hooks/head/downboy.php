<script>
$(function() {
	downBoy('footer'); // Run on load
	window.onresize = function() { // On Resize
		downBoy('footer'); // Run Again
	};
});
</script>
