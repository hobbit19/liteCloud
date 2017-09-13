<?php
// Подключаем функции
include "{$_SERVER['DOCUMENT_ROOT']}/{$application['dir']}/functions.php";
// Получаем входящие запросы окна
$windowquery = Cloud::$application->winquery();
// Если есть запрос на открытие окна
if(isset($_POST['function']) && !empty($_POST['function'])) $notice = getwindow($_POST['function']);
// Существует ли запрос на смену языка
if(isset($windowquery['language']) && !empty($windowquery['language'])) Cloud::language($windowquery['language']);
// Вычисляем свободное пространства сервера
$space = Cloud::$system->freespace();
// Выводим процент занятости
$application['api']['template']->set("percent", $space['percent']);
// Выводим свободное пространство
$application['api']['template']->set("free", $space['freespace']);
// Выводим общее пространство
$application['api']['template']->set("total", $space['totalspace']);
// Выводим занятое пространство
$application['api']['template']->set("busy", $space['busyspace']);
// Выводим имя пользователя
$application['api']['template']->set("nameuser", Cloud::$profile['name']);
// Присваиваем полный путь к приложению
$application['api']['template']->set("dir", $application['dir']);
// Выводим аватарку пользователя
$application['api']['template']->set("avatar", "/{$application['dir']}/avatar.png");
// Выводим аватарку пользователя
$application['api']['template']->set("urlapp", $application['api']['urlapp']);
// Генерация шаблона
return array
(
	'style' => $application['api']['template']->display('style.css'),
	'html'  => $application['api']['template']->display('style.tmp'),
	'notice' => array('title' => $notice['title'], 'message' => $notice['message']),
	"menu" => array([Cloud::$application->language(["exit"]), "/?application=-1", 1])
);