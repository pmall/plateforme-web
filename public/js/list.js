$(document).ready(init());

function init(){

	setInterval(updateJobs, 2000);
	$('.job').on('submit', function(){ return addJob(this); });
	$('.delete').on('submit', function(){ return deleteItem(this); });

}

function updateJobs(){

	$.ajax({
		url:'/elexir2/index.php/jobs',
		type:'get',
		success:function(text){
			$('#jobs').html(text);
		}
	});

}

function addJob(form){

	var elem = $(form);
	var type = elem.data('type');
	var id_project = elem.data('id_project');
	var id_analysis = elem.data('id_analysis');

	$.ajax({
		url:'/elexir2/index.php/job',
		type:'post',
		data:{
			'job[type]':type,
			'job[id_project]':id_project,
			'job[id_analysis]':id_analysis
		},
		success:function(text){

			if(text == 'ok'){

				updateJobs();

			}else{

				alert(text);

			}

		}
	});

	return false;

}

function deleteItem(form){

	var elem = $(form);
	var id_elem = elem.data('id');
	var name_elem = elem.data('name');
	var type_elem = elem.data('type');
	var id_html = '#' + type_elem + '_' + id_elem;
	var url = '/elexir2/index.php/';

	if(confirm('Voulez vous vraiment supprimer ' + name_elem + ' ?')){

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
