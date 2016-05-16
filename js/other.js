/*
var toggle = function (class1, class2) {
	
	$(class1).click(function() {
		if($(this).is(':checked')) {
			$(class2).show();
		} else {
			$(class2).hide();
		}
	});
};
//alert("Hello World!");
	
	/*
	$('body').click(function() {
		$(this).animate({padding: '20em 20em'}, 2000);
		//$(this).animate({padding: '20em 20em'}, 2000);
	});
	*/
	//toggle('#custom_basis', '#custom_basis_options');	
	//toggle('#Dunning', '#Dunning_fields');	
	//toggle('#Pople', '#Pople_fields');
/*
	$('#custom_basis').click(toggle('#custom_basis_options'));
	$('#Dunning').click(toggle('#Dunning_fields'));
	$('#Pople').click(toggle('#Pople_fields'));

var toggle = function (classToToggle) {
	console.log('clicked!');
	if($(this).is(':checked')) {
		$(classToToggle).show();
	} else {
		$(classToToggle).hide();
	}
};

$('#custom_basis').on("click", toggle('#custom_basis_options'));

/*
var toggle = function (className, obj) {
	var $input = $(obj);
	if ($input.prop('checked')) $(className).hide();
	else (className).show();
};
*/
