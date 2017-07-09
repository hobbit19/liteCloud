<?php
// If a person is not authorized - return a response
if(!Cloud::$profile['is_login']) return;

// Get information about the application
$application = Cloud::$application->init($_POST['application'], APPLICATION_ID);

// Checking the application for integrity
if(count($application) == 0) return Cloud::ajaxerror($CONFIG['app_error'][1]);
else if(!isset($application['config']['name']) && ! isset($application['dir']))
	return Cloud::ajaxerror($CONFIG['app_error'][2]);
else if(!$application['files']) return Cloud::ajaxerror($CONFIG['app_error'][0]);

// Running the application code
$app = include "{$_SERVER['DOCUMENT_ROOT']}/{$application['dir']}/code.php";

// Variables for menu generation
$menu = NULL;
$qapp = mysqli_query(
	Cloud::$mysqli, "SELECT `id`, `config` FROM `apps` WHERE `type`='0'"
);
// Menu generation cycle
while($rows = mysqli_fetch_assoc($qapp))
{
	// Decode application configuration
	$appinfo = (array)json_decode($rows['config'], true);
	
	// Build menu blocks for a template
	$menu .= "<a href=\"/?application={$rows['id']}\" class=\"appblock"
	// The condition for the output of the active application
	.(($application['id'] == $rows['id']) ? " active" : "").
	"\">{$appinfo['name']}</a>";
}
// Return the response
return array(
	'status'	=> 1, // The status of the correct application operation
	'html'		=> $app['html'], // Application content
	'title'		=> $application['config']['name'], // Application name
	'menu'		=> $menu, // Modified menu
	'notice'	=> Cloud::$application->notice(
		$app['notice']['title'],
		$app['notice']['message']
	) // Notification window
);
