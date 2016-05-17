$('document').ready(function () {

	// Toggle password fields when adding new link
	$("#inputSecret").change(function() {
		if(this.checked) {
			$(".passwordGroup").toggleClass('hidden');
		} else {
			$(".passwordGroup").toggleClass('hidden');
		}
	});
	
});