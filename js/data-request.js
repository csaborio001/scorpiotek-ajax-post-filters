jQuery(function($){
	$('.chosen-select').change(function(){
		var filter = $('#filter');
		$.ajax({
			url:filter.attr('action'),
			data:filter.serialize(), // form data
			type:filter.attr('method'), // POST
			beforeSend:function(xhr){
				//filter.find('button').text('Processing...'); // changing the button label
			},
			success:function(data){
				//filter.find('button').text('Reset Filters'); // changing the button label back
				$('#response').html(data); // insert data
			}
		});
		return false;
	});
});

jQuery(function($) {
	$('#filter-button').click(function() {
		event.preventDefault();
		// $('#category_id_chosen').prop('selectedIndex', 0);
		$('[name=city').prop('selectedIndex', 0);
		//$('.chosen-container').
	});
});