<?php

class Dir{

	private $file;

	private static function Readdir($dir){

		$files = array();

		$dh = opendir($dir);

		while($file = readdir($dh)){

			$files[] = $file;

		}

		closedir($dh);

		return $files;

	}

	public static function All(){
		global $config;

		$validDirs = array();

		$files = Dir::Readdir($config['celdir']);

		foreach($files as $file){

			$dir = new Dir($file);

			if($dir->isValid()){

				$validDirs[] = $dir;

			}

		}

		return $validDirs;

	}

	public function __construct($file){

		$this->file = trim($file, '/');

	}

	public function getCelfiles(){
		global $config;

		$validCelfiles = array();

		$files = Dir::Readdir($config['celdir'] . '/' . $this->file);

		foreach($files as $file){

			if($this->isCelfile($file)){

				$validCelfiles[] = $file;

			}

		}

		return $validCelfiles;

	}

	private function isValid(){

		return preg_match(
			'/^[a-zA-Z0-9][a-zA-Z0-9_\-]+[a-zA-Z0-9]$/',
			$this->file
		);

	}

	private function isCelfile($file){

		return preg_match(
			'/\.(cel|CEL)$/',
			$file
		);

	}

	public function __toString(){

		return $this->file;

	}

}

?>
