<?php
try
{
	// Connecting libraries and configuration
	require_once __DIR__ . "/lib/main.class.php";
	require_once __DIR__ . "/lib/guard.class.php";
	require_once __DIR__ . "/lib/temp.class.php";
	require_once __DIR__ . "/lib/app.class.php";
	require_once __DIR__ . "/lib/system.class.php";
	require_once __DIR__ . "/resources/config.php";
	
	// Filling pointers and checking the database
	Cloud::handshake($CONFIG);
	if(!Cloud::$mysqli) exit(Cloud::error("Невозможно подключиться к бд."));

	// Output of application content
	if(isset($_GET['content']) && Cloud::$profile['is_login'])
		exit(json_encode(include __DIR__ . "/resources/application.php"));
	else if (isset($_GET['appstyle']) && Cloud::$profile['is_login'])

	// Outputting an application style sheet
	exit(include __DIR__ . "/resources/style.php");

	// API for retrieving files
	else if (isset($_GET['get']) && Cloud::$profile['is_login'])
		exit(include __DIR__ . "/resources/filesource.php");

	// Output of the interface of the specified controller
	$interface = Cloud::__interface($CONFIG['controller']);

	// Check for redirects
	if($interface['status'] != 1)
		header("Location: {$interface['location']}");
	exit;
}catch (Exception $e)
{
	exit(Cloud::error($e->getMessage()));
}
