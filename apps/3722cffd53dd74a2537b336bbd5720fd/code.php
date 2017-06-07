<?php
// Переменная текущей директории
$directory = "/";
// Если параметр каталога существует и не пустой
if(isset($_POST['dir']) && !empty($_POST['dir']))
// Применение пути к каталогу и если такого пути нет - выводим ошибку
if(!is_dir($directory = urldecode($_POST['dir']))) Cloud::ajaxerror("Joooooooo");
// Начинаем сканирование текущего каталога
$objects = Cloud::$system->fromdir($directory);
// Получаем контент с вспомогательных скриптов
$dirs 	= include "{$_SERVER['DOCUMENT_ROOT']}/{$application['dir']}/dirs.php";
$files 	= include "{$_SERVER['DOCUMENT_ROOT']}/{$application['dir']}/files.php";
// Генерация шаблона
return array(
    'style' => $application['api']['template']->display('style.css'),
    'html'  => "<div id=\"content_file\">{$dirs}{$files}</div>"
);
