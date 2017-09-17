<?php
// Получение входящих запросов
$window	= Cloud::$application->winquery();
$appqr	= Cloud::$application->appquery();
// Скрипт функций
include "{$_SERVER['DOCUMENT_ROOT']}/{$application['dir']}/functions.php";
// Переменная текущей директории
$directory = "/";
// Если параметр каталога существует и не пустой
if(isset($_POST['dir']) && !empty($_POST['dir']))
// Применение пути к каталогу и если такого пути нет - выводим ошибку
if(!is_dir($directory = Guard::slashes(urldecode($_POST['dir'])))) Cloud::ajaxerror("Joooooooo");
// Начинаем сканирование текущего каталога
$objects = Cloud::$system->fromdir($directory);
// Получаем контент с вспомогательных скриптов
$dirs 	= include "{$_SERVER['DOCUMENT_ROOT']}/{$application['dir']}/dirs.php";
$files 	= include "{$_SERVER['DOCUMENT_ROOT']}/{$application['dir']}/files.php";
$bread	= include "{$_SERVER['DOCUMENT_ROOT']}/{$application['dir']}/breadcrumbs.php";
// Дополнительные функции с модальных окон
include "{$_SERVER['DOCUMENT_ROOT']}/{$application['dir']}/modalfn.php";
// Выставление переменных для вывода содержимого
$application['api']['template']->set('dirs',	$dirs);
$application['api']['template']->set('files',	$files['content']);
$application['api']['template']->set("css",		$application['dir']);
$application['api']['template']->set('bread',	$bread);
// Переменные для генерации модального окна от файлов
if(isset($files['notice']['message']) && !empty($files['notice']['message']))
{
	// Контент модального окна
	$title = $files['notice']['title'];
	$message = $files['notice']['message'];
// Переменные для генерации модального окна от функций
}else if(isset($functions['message']) && !empty($functions['message']))
{
	// Контент модального окна
	$title = $functions['title'];
	$message = $functions['message'];
// Если окна нету
}else $title = $message = NULL;
// Генерация шаблона
return array
(
	'style' => $application['api']['template']->display('style.css'),
	'html'  => $application['api']['template']->display('style.tmp'),
	'notice' => array('title' => $title, 'message' => $message),
	'menu' => array(
		[Cloud::$application->language(["menu","upl"]), "{$application['api']['urlapp']}&dir={$directory}&fn=upload", 0], 
		[Cloud::$application->language(["menu","crt"]), "{$application['api']['urlapp']}&dir={$directory}&fn=create", 0]
	)
);
