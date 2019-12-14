<?php
// проверка версии php (7, согласно описанию вакансии)
if (version_compare(PHP_VERSION, '7.0.0', '<')) {
	exit('PHP 7.0 or higher required');
}
// файл с константами
require __DIR__ . '/includes/defines.php';

if(DEBUG){
//	включить сообщения об ошибках, если установлен флаг DEBUG в defines.php
	ini_set('display_errors', 'On');
	ini_set('error_reporting', E_ALL);
}

// конфигурация БД
require __DIR__ . '/db_cfg.php';
// функция автозагрузки классов
require __DIR__ . '/includes/autoload.php';

// запустить приложение
(new \App\classes\Sknt())->run();
