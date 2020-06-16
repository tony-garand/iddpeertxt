$(document).ready(function() {
	$('#add_field').on('click', function (e) {
		let field = $('#fields').val();
		let input = $('#reply_body');

		input.val(input.val() + '[[' + field + ']]');
		input.focus();
	});
});