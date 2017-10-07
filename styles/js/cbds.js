$( document ).ready(function() {
	$('.ui.sticky.headers').sticky({
	  debug: true,
	  verbose: true,
	  observeChanges: true,
	  context: '.main-container'
	});
	$('.pos-nex a, .pos-pre a').click(function(e) {
		/* Act on the event */
		e.preventDefault();
		var href = $(this).attr('href');
		window.location.href = href;
	});
	$("body").keydown(function(e) {
		if(e.keyCode == 37) { // left 
			$('.pos-nex a').trigger('click');
		}
		if(e.keyCode == 39) { // right
			$('.pos-pre a').trigger('click');	}	
	});
});
