<?php
// Notification Array
$notice = array();

// The condition for the _GET parameter file
if(isset($_POST['file']) && !empty($_POST['file']))
	$notice = filewindow($_POST['dir'], $_POST['file'], $application['api']['urlapp'], $application['dir']);

// Template variable
$content = NULL;

// Generating files template
for($i=0;$i<count($objects['files']);$i++)
{
	// The condition of the next step
	$step = ($directory[strlen($directory) - 1] == '/') ? $directory : "{$directory}/";

	// Defining an array for a file
	$file = fileinfo($step, $objects['files'][$i]['name'], $application['api']['urlapp'], $application['dir']);

	// Create an abbreviated file name
	$name = (strlen($objects['files'][$i]['name']) > 13) ?
		mb_substr($objects['files'][$i]['name'], 0, 13, 'UTF-8') . ".." : $objects['files'][$i]['name'];
		
	// Apply template
	$content .= "
	<a {$file['html']} href=\"{$file['href']}\" id=\"block_file\">
		<p>{$file['image']}</p>
		<div id=\"title_file\">{$name}</div>
	</a>";
}
// Returning the template
return array('content' => $content, 'notice' => $notice);
