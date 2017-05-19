<?php
/*
	Приложение: 	Менеджер памяти
	Дата написания: 09.02.16
*/
if(isset($_POST['dir']) && !empty($_POST['dir']))
{
	$dir = Project::fullpath(urldecode($_POST['dir']));
	if(!is_dir($dir)) $dir = '::error';
}else
{
	$dir = Project::fullpath(urldecode("/"));
	$_POST['dir'] = '/';
}

$title_app = "Менеджер памяти";
$APP['tmp']->set('is_upload', 0);
$APP['tmp']->set('is_create', 0);

if($dir == '::error')
{
	$APP['tmp']->set_cycle('content', 'kjk');
}else
{
	if(isset($_POST['upload']))
	{
		$APP['tmp']->set('content', 'none');
		$APP['tmp']->set('is_upload', 1);
		$title_app = "Загрузка файла";

	}elseif(isset($_POST['create']))
	{
		$title_app = "Создание файла/каталога";
		$APP['tmp']->set('content', '
			<div id="block_ff">
				<a href="#">
					<div class="cont_n"><img src="'.$APP['dir'].'/icons/dir.png"></div>
					<div class="title_n">Директория</div>
				</a>
			</div>
			<div id="block_ff">
				<a href="#">
					<div class="cont_n"><img src="'.$APP['dir'].'/icons/txt.png"></div>
					<div class="title_n">Текстовый файл</div>
				</a>
			</div>
		');

		$APP['tmp']->set('is_create', 1);
			
	}else include(ROOT_PATH.$APP['dir']."/range.php");
}

return array(
	'html' 	=> $APP['tmp']->display('html.tmp', true), 
	'css' 	=> $APP['tmp']->display('app.css', 	true), 
	'title' => $title_app
);