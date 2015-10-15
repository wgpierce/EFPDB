//We can put plenty of fancier animations in this document

var main = function() {
	//alert("Hello World!");
	/*
	$('body').click(function() {
		$(this).animate({padding: '20em 20em'}, 2000);
	});
	*/
	
	$('#wut').click(function() {
		console.log(document.title);
		//$(this).animate({padding: '20em 20em'}, 2000);
	});
	
};






$(document).ready(main);