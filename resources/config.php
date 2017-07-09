<?php
/*
Array of project configuration
     Content:
     1 - Data array for connection to the database
     2 - The main project controllers and their paths
     3 - Path to the html project template
     4 - Current mode in which the project is running
     5 - Application error arrays
     8 - The working area of the cloud
*/
$CONFIG = array(
	'mysql' => array(
		'host'      => 'localhost',
		'username'  => 'root',
		'password'  => 'pass',
		'dbname'    => 'xcloud_regedit'
	),

	'controller' => array(
		1 => array(
			'include'   => '/resources/application.php'
		),

		2 => array(
			'include'   => '/resources/account.php'
		)
	),

	'template' => '/resources/templates/',
	'debug' => 1,

	'app_error' => array(
		0 => '<b>The application files are corrupted.</b>
		This could happen during the process of incorrect installation of the application, or its removal.
		We advise you to delete all entries in the registry about the application or its files.',

		1 => '<b>The application was not installed.</b>
		The application launcher did not receive content from the called application.
		Try downloading the application and installing it.',

		2 => '<b>The configuration of the application is corrupted.</b>
		The application key data stored in the registry was corrupted.
		We advise you to delete all entries in the registry about the application or its files.'
	),

	'path' => '/home/pi'
);
/*
	Constants of the project
	Appointments: Application search type
*/
define('APPLICATION_ID',    0);
define('APPLICATION_DIR',   1);
define('APPLICATION_ALIAS', 2);
/*
	Assignments: Directory / file search options
*/
define('ISDIR',   0);
define('ISFILE',  1);
