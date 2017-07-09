<?php
// Template variable
$content = NULL;

// Generating a directories template
for($i=0;$i<count($objects['dirs']);$i++)
{
	// The condition of the next step
	$step = ($directory[strlen($directory) - 1] == '/') ? $directory : "{$directory}/";

	// Create a link to go to the new directory
	$url = "{$application['api']['urlapp']}&dir={$step}{$objects['dirs'][$i]['name']}";

	// Create an abbreviated directory name
	$name = (strlen($objects['dirs'][$i]['name']) > 16) ?
		mb_substr($objects['dirs'][$i]['name'], 0, 16, 'UTF-8') . ".." : $objects['dirs'][$i]['name'];
		
	// Applying a template
	$content .= "
	<a href=\"{$url}\" id=\"block_file\">
		<p><img src=\"/{$application['dir']}/images/dir.png\"></p>
		<div id=\"title_file\">{$name}</div>
	</a>";
}
// Returning the template
return $content;
