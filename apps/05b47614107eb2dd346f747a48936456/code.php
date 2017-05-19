<?php
$config = array(
	'html' 	=> '<center style="padding-top:100px;">Нет выбраного файла.</center>', 
	'title' => 'Редактор txt', 
	'css' 	=> $APP['tmp']->display('style.css', true)
);

if(isset($_POST['file_path']) && !empty($_POST['file_path']))
{
	$file = Project::fullpath($_POST['file_path']);
	if(is_file($file))
	{
		$content = file_get_contents($file);
		$config['html'] = "<div id=\"buttons_panel\"><span id=\"button\">sdfsdfsdfdfs</span><a href=\"\" id=\"button\">Сохранить</a></div>
		<textarea id=\"textbox\">".$content."</textarea>";
	}

	return $config;
}else return $config;