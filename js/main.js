
var main = function() {

	//Update the current menu item
	var z = location.pathname.substring(location.pathname.lastIndexOf('/')+1);
	if (z == 'upload_action.php' || z == 'run_GAMESS.php') z = 'upload.php';
	if (z == 'view_mol.php') z = 'database_list.php';
	var element = z ? document.getElementById(z) : document.getElementById('index.php'); //JQuery refuses to work here
	
	element.classList.add('selected');
	
		
	
//toggle showing options on the jobs submission screen
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