$( document ).ready(function() {
	$('.ui.sticky.headers').sticky({
	  debug: true,
	  verbose: true,
	  observeChanges: true,
	  context: '.main-container'
	});
});
