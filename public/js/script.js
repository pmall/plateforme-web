$(document).ready(init());

function init(){

	// On active le bouton pour fermer les notice
	$(".alert").alert()

	// On active les dropdown
	$('.dropdown-toggle').dropdown()

	// =====================================================================
	// On active la mise a jour des jobs
	// =====================================================================

	// On recharge la liste de job
	setInterval(updateJobs, 2000);
	$('.job').live('click', function(){ return addJob(this); });
	$('.delete').live('click', function(){ return deleteItem(this); });

	// =====================================================================
	// Formulaire projet
	// =====================================================================

	// Quand on met le type a ggh on met l'organisme a humain
	$('#project_type').live('change', function(){

		if($(this).val() == 'ggh'){

			$('#project_organism').val('human');

		}

	});

	// =====================================================================
	// Formulaire analyse
	// =====================================================================

	// Ca marche pas quand on apelle set letter maintenant ????

	// Quand on change la valeur de type on redéfini les lettres possibles
	$('#analysis_type').live('change', function(){

		setLetters();

	});

}

// Met a jour les lettres dans le formulaire analyse
function setLetters(){

	var type = $('#analysis_type').val();

	$('.group').each(function(){

		var group = $(this);

		if(type == 'compose'){

			if(group.children().length == 3){

				group.append('<option value="C">C</option>');
				group.append('<option value="D">D</option>');

			}

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

// Met a jout les jobs
function updateJobs(){

	$.ajax({
		url:'/plateforme2/index.php/jobs',
		type:'get',
		success:function(text){
			$('#jobs').html(text);
		}
	});

}

// Ajouter un job
function addJob(e){

	var elem = $(e);
	var type = elem.data('type');
	var id_project = elem.data('id_project');
	var id_analysis = elem.data('id_analysis');

	$.ajax({
		url:'/plateforme2/index.php/job',
		type:'post',
		data:{
			'job[type]':type,
			'job[id_project]':id_project,
			'job[id_analysis]':id_analysis
		},
		success:function(text){

			if(text == 'ok'){

				updateJobs();

				if(type == 'preprocessing'){

					$('#notice_dirty_' + id_project).fadeOut();

				}

			}else{

				alert(text);

			}

		}
	});

	return false;

}

// Supprimer un élement
function deleteItem(e){

	var elem = $(e);
	var id_elem = elem.data('id');
	var type_elem = elem.data('type');
	var name_elem = elem.data('name');
	var id_html = '#' + type_elem + '_' + id_elem;
	var url = '/plateforme2/index.php/';

	if(confirm('Voulez vous vraiment supprimer ' + name_elem + ' ?\n(Toutes les données correspondantes seront supprimées)')){

		if(type_elem == 'analysis'){

			id_project = elem.data('id_project');

			url+= 'project/' + id_project + '/' + type_elem + '/' + id_elem;

		}else{

			url+= type_elem + '/' + id_elem;

		}

		$.ajax({
			url:url,
			type:'delete',
			success: function(text) {

				if(text == 'ok'){

					var elem = $(id_html);

					elem.fadeOut('slow', function(){
						elem.remove();
					});

				}

			}
		});

	}

	return false;

}
