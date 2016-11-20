<?php
	function __autoload($classname){
		$repClasses='classes/';
		require $repClasses.$classname.'.class.php';
	}
?>