$(function() {

	// Get datediff
	$('.form').submit(function(e) {
		e.preventDefault();
		
		var dataString = $('.form').serialize();
		dataString += "&ajax=true";

		$.ajax({
			method: 'POST',
			url: '/',
			data: dataString,
			cache: false,
			dataType: 'json',
			success: function(data) {
				// console.log(data);
				$('#result').html(data);
			},
			error: function(e) {
				console.log(e);
			}
		});
	});


	// Get data from test
	$('#run_test').click(function() {
		var dataString = $('.form').serialize();

		$.ajax({
			method: 'POST',
			url: '/test.php',
			data: dataString,
			cache: false,
			dataType: 'json',
			success: function(data) {
				// console.log(data);
				$('#test_result').html(data.message + '<hr>' +  'Total days: ' + data.total_days);
			},
			error: function(e) {
				console.log(e);
			}
		});
	});

	// next script
});