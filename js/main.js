//We can put plenty of fancier animations in this document

//NCLICK="TOGGLE(\'CUSTOM_BASIS_OPTIONS\', THIS)"

var main = function() {
	//if(parent.frames.length != 0) top.location=location.pathname.substring(1);
	$('#custom_basis').click(function() {
		console.log('clicked!');
		if($(this).is(':checked')) {
			$('#custom_basis_options').show();
		} else {
			$('#custom_basis_options').hide();
		}
	});
	$('#Dunning, #Pople').click(function() {
		console.log('clicked!');
		if($('#Dunning').is(':checked')) {
			$('#Dunning_fields').show();
		} else {
			$('#Dunning_fields').hide();
		}
		if($('#Pople').is(':checked')) {
			$('#Pople_fields').show();
		} else {
			$('#Pople_fields').hide();
		}
	});
};

$(document).ready(main);