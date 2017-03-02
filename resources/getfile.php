<?php
if(!isset($_GET['file']) || empty($_GET['file']))
{
	header('Location: /?path=home');
	exit;
}

$file_w = Project::doc_way($_GET['file'], 1);

if(is_file($file_w)) 
{

	if(ob_get_level()) 
		ob_end_clean();
	header('Content-Description: File Transfer');
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename=' . basename($file_w));
	header('Content-Transfer-Encoding: binary');
	header('Expires: 0');
	header('Cache-Control: must-revalidate');
	header('Pragma: public');
	header('Content-Length: ' . filesize($file_w));
	readfile($file_w);
	exit;
}