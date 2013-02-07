<?php

class Response{

	private $root;
	private $headers;
	private $mime_type;
	private $body;
	private $redirect;
	private $isSent;

	public function __construct($root = ''){

		$this->root = trim($root, '/');
		$this->headers = array();
		$this->mime_type = 'text/html';
		$this->body = '';
		$this->redirect = '';
		$this->isSent = false;

	}

	public function addHeader($header){

		$this->headers[] = $header;

	}

	public function addHeaders(Array $headers){

		foreach($headers as $header){

			$this->addHeader($header);

		}

	}

	public function setContentType($mime_type){

		$this->mime_type = $mime_type;

	}

	public function setBody($content){

		$this->body = $content;

	}

	# Ajoute une valeur dans le flash
	public function setFlash($key, $value){

		Flash::getInstance()->set($key, $value);

	}

	public function isSent(){

		return $this->isSent;

	}

	public function redirect($url){

		$this->redirect = trim($url, '/');

	}

	public function send(){

		if($this->redirect != ''){

			$root = ($this->root == '')
				? $this->root
				: '/' . $this->root;

			header('location: ' . $root . '/' . $this->redirect);

		}else{

			foreach($this->headers as $header){

				header($header, false);

			}

			header('content-type: ' . $this->mime_type, false);

			echo $this->body;

		}

		$this->isSent = true;

	}

}

?>
