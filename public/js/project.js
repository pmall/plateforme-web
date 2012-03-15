$(document).ready(init());

function init(){

	$('#type').live('change', function(){

		if($(this).val() == 'ggh'){

			$('#organism').val('human');

		}

	});

}
