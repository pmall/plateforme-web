$(document).ready(init());

function init(){

	// Au démarage on défini les lettres possibles
	setLetters();

	// Quand on change la valeur de type on redéfini les lettres possibles
	$('#type').live('change', function(){ setLetters(); });

}

function setLetters(){

	var type = $('#type').val();

	$('.group').each(function(){

		var group = $(this);

		if(type == 'J/O'){

			group.append('<option value="C">C</option>');
			group.append('<option value="D">D</option>');

		}else{

			group.children().each(function(){

				var elem = $(this);

				if(elem.val() == 'C' || elem.val() == 'D'){

					elem.remove();

				}

			});

		}

	});

}
