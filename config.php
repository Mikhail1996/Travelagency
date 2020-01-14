<?php

function __autoload($classname){
	switch($classname[0])
	{
		case 'C':
			include_once("c/$classname.php");
			break;
		case 'M':
			include_once("m/$classname.php");
			break;
	}
}

define('BASE_URL', $_SERVER["HTTP_HOST"]);
define('BASE_PATH', $_SERVER["DOCUMENT_ROOT"]);

define('MYSQL_SERVER', 'localhost');
define('MYSQL_USER', 'root');
define('MYSQL_PASSWORD', '');
define('MYSQL_DB', 'myshop');

?>