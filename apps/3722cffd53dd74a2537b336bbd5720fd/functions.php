<?php
// Determining the output properties of a file
function fileinfo($fullpath = '', $finename = '', $appurl = '', $dir = '')
{
	// If an empty variable
	if(empty($fullpath) || empty($finename) || empty($appurl) || empty($dir) ||
		!file_exists(Cloud::workzone() . "{$fullpath}{$finename}")) return array();

	// Array generation by type
	if(Guard::is_image(Cloud::workzone() . "{$fullpath}{$finename}"))
	return array(
		"image"	=> "<img src=\"/?get={$fullpath}{$finename}\" style=\"border-radius:5px;\">",
		"href"	=> "",
		"html"	=> ""
	);

	// If the condition does not pass
	else return array(
		"href"	=> "{$appurl}&file={$finename}&dir={$fullpath}",
		"html"	=> "",
		"image"	=> "<img src=\"/{$dir}/images/file.png\">"
	);
}
// File information window
function filewindow($fullpath = '', $filename = '', $appurl = '', $dir = '')
{
	// Get information about the file
	$about = Cloud::$system->aboutfile("{$fullpath}{$filename}");

	// Window name generation
	$title = (strlen($filename) > 32) ?
		mb_substr(Guard::escape($filename), 0, 32, 'UTF-8') . ".." : Guard::escape($filename);

	// Gathering information about the file
	$info = fileinfo($fullpath, $filename, $appurl, $dir);
	
	// Returning the contents of the window
	return array('title' => Guard::escape($filename), 'message' => '
	<table id="actionstable"><tr>
	<td class="left">' . $info['image'] . '</td>
	<td class="right"><div>
	<input filter-input="true" style="width:363px;" placeholder="File name" value="' . $filename . '" type="text" name="kkkk">
	<winquery>Rename</winquery></div><table id="tableinwin">
	<tr><td valign="top" class="left_text">Date of change</td>
	<td>' . $about['time'] . '</td></tr><tr><td valign="top" class="left_text">File weight</td>
	<td>' . $about['size'] . '</td></tr><tr><td valign="top" class="left_text">File permissions</td>
	<td>' . $about['rules'] . '</td></tr></table></td></tr></table>');
}
