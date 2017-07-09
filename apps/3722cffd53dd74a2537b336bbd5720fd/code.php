<?php
// Getting incoming requests
$window = Cloud::$application->winquery();
$appqr = Cloud::$application->appquery();

// Script functions
include "{$_SERVER['DOCUMENT_ROOT']}/{$application['dir']}/functions.php";

// Variable current directory
$directory = "/";

// If the directory parameter exists and is not empty
if(isset($_POST['dir']) && !empty($_POST['dir']))

// Applying path to the directory. If there is no such way, then we derive an error
if(!is_dir($directory = urldecode($_POST['dir']))) Cloud::ajaxerror("Joooooooo");

// Start scanning the current directory
$objects = Cloud::$system->fromdir($directory);

// Get content from auxiliary scripts
$dirs 	= include "{$_SERVER['DOCUMENT_ROOT']}/{$application['dir']}/dirs.php";
$files 	= include "{$_SERVER['DOCUMENT_ROOT']}/{$application['dir']}/files.php";

// Generating a template
return array(
	'style' => $application['api']['template']->display('style.css'),
	'html'  => "<div id=\"content_file\">{$dirs}{$files['content']}</div>",
	'notice' => array('title' => $files['notice']['title'], 'message' => $files['notice']['message'])
);
