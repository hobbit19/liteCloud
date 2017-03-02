<?php
$file_w = Project::doc_way($_POST['file'], 1);
$dire_w = Project::doc_way($_POST['file'], 0);

if(is_file($file_w)) 
{
	if(isset($_POST['remove']))
	{
		unlink($file_w);
		header('Location: /?path=home');
		exit;
	}

	$array = Project::about_doc($file_w);
	$APP['tmp']->set('title', 'файле '.basename($file_w));
	$APP['tmp']->set('name', basename($file_w));
	$APP['tmp']->set('way', str_replace(basename($file_w), '', $file_w));
	$APP['tmp']->set('size', $array['size']);
	$APP['tmp']->set('type', 1);
	$title = 'файле';
}elseif(is_dir($dire_w))
{
	if(isset($_POST['remove']))
	{
		rmdir($dire_w);
		header('Location: /?path=home');
		exit;
	}

	$array = Project::about_doc($dire_w);
	$name = explode('/', $dire_w);
	$APP['tmp']->set('title', 'каталоге '.basename($dire_w));
	$APP['tmp']->set('name', $name[count($name) - 1]);
	$APP['tmp']->set('way', $dire_w);
	$APP['tmp']->set('size', $array['empty']);
	$APP['tmp']->set('type', 2);
	$title = 'каталоге';
}

return array(
	'html' 	=> $APP['tmp']->display('fileinfo.tmp', true), 
	'css' 	=> $APP['tmp']->display('main.css', 	true), 
	'title' => 'Информация о '.$title
);