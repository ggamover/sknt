<?php

if (version_compare(PHP_VERSION, '7.0.0', '<')) {
	exit('PHP 7.0 or higher required');
}

require __DIR__ . '/includes/defines.php';

if(DEBUG){
	ini_set('display_errors', 'On');
	ini_set('error_reporting', E_ALL);
}

require __DIR__ . '/includes/autoload.php';

(new \App\classes\Sknt())->run();
