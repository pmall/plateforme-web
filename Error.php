<?php

class Error{

	private $message;
	private $fields;

	public function __construct($message, $fields = ''){

		$this->message = $message;

		$this->fields = (is_array($fields))
			? $fields
			: array($fields);

	}

	public function hasField($field){

		return in_array($field, $this->fields);

	}

	public function __toString(){

		return $this->message;

	}

}

?>
