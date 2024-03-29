<?php

function displayErrors(Model $model){

	$html = '';

	if(!$model->isValid()){

		$html.= '<div class="alert alert-error">';
		$html.= '<strong>Erreurs !</strong>';
		$html.= '<ul>';

		foreach($model->getErrors() as $error){

			$html.= '<li>' . $error . '</li>';	

		}

		$html.= '</ul>';
		$html.= '</div>';

	}

	return $html;

}

function hasError(Model $model, $field){

	$html = '';

	if($model->hasError($field)){

		$html.= ' error';

	}

	return $html;

}

function label(Model $model, $name, $text){

	$modelName = strtolower(get_class($model));

	$id = $modelName . '_' . $name;

	$html = '';

	$html.= '<label for="' . $id . '" class="control-label' . hasError($model, $name) . '">' . $text . '</label>' . "\n";

	return $html;

}

function field(Model $model, $name, $type, Array $options = array()){

	$modelName = strtolower(get_class($model));

	$options['id'] = $modelName . '_' . $name;
	$options['name'] = $modelName . '[' . $name . ']';
	$options['type'] = $type;

	# Si on a passé une valeur dans le tableau option on la garde
	# sinon on donne la valeur de l'attribut du modele
	if(!array_key_exists('value', $options)){

		$options['value'] = $model->$name;

	}

	# On efface la valeur si c'est un password
	if($type == 'password'){ $options['value'] = ''; }

	# On crée la chaine d'option
	$options_string = implode(' ', array_map(function($k, $v){ return $k . '="' . $v . '"'; }, array_keys($options), array_values($options)));

	# On retourne le champ
	return '<input ' . $options_string . ' class="span4" />' . "\n";

}

function select(Model $model, $name, Array $options){

	$modelName = strtolower(get_class($model));

	$id = $modelName . '_' . $name;

	$html = '<select id="' . $id . '" name="' . $modelName . '[' . $name . ']' . '" class="span4">' . "\n";
	$html.= '<option value=""></option>' . "\n";

	foreach($options as $value => $text){

		$selected = '';

		if($model->$name == $value){

			$selected = ' selected="selected"';

		}

		$html.= '<option value="' . $value . '"' . $selected . '>'; 
		$html.= $text;
		$html.= '</option>' . "\n";

	}

	$html.= '</select>' . "\n";

	return $html;

}

function textarea(Model $model, $name){

	$modelName = strtolower(get_class($model));

	$id = $modelName . '_' . $name;

	$html = '<textarea id="' . $id . '" name="' . $modelName . '[' . $name . ']' . '" class="span4">' . "\n";
	$html.= $model->$name;
	$html.= '</textarea>' . "\n";

	return $html;

}

function checkbox(Model $model, $name, $value){

	$modelName = strtolower(get_class($model));

	# On fait un tableau de valeur
	$options = array(
		'id' => $modelName . '_' . $name,
		'name' => $modelName . '[' . $name . ']',
		'type' => 'checkbox',
		'value' => $value
	);

	# Si la value fixe et la value courante est identique
	if($model->$name == $value){ $options['checked'] = 'checked'; }

	# On crée la chaine d'option
	$options_string = implode(' ', array_map(function($k, $v){ return $k . '="' . $v . '"'; }, array_keys($options), array_values($options)));

	# On retourne le champ
	return '<input ' . $options_string . ' />' . "\n";

}

?>
