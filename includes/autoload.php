<?php

spl_autoload_register(function($className){
	$appPrefix = "App\\";
	if(strpos($className, $appPrefix) !== 0){
		return;
	}

	$className = substr($className, strlen($appPrefix));

	$path = APPDIR . "/" . str_replace("\\", "/", $className) . ".php";

	if(isset($path) && file_exists($path)){
		include($path);
	}
});