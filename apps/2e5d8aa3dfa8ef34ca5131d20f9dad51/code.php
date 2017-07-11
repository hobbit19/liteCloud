<?php
// Подключаем функции
include "{$_SERVER['DOCUMENT_ROOT']}/{$application['dir']}/functions.php";
// Получаем входящие запросы окна
$windowquery = Cloud::$application->winquery();
// Если есть запрос на открытие окна
if(isset($_POST['function']) && !empty($_POST['function']))
	$notice = getwindow($_POST['function']);
// Существует ли запрос на смену языка
if(isset($windowquery['language']) && !empty($windowquery['language']))
	Cloud::language($windowquery['language']);
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
// Извлечение фраз из массива
$application['api']['template']->set("ava", Cloud::$application->language(array('links','avatar')));
$application['api']['template']->set("profile", Cloud::$application->language(array('links','profile')));
$application['api']['template']->set("user", Cloud::$application->language(array('links','users')));
$application['api']['template']->set("zone", Cloud::$application->language(array('links','zone')));
$application['api']['template']->set("rules", Cloud::$application->language(array('links','rules')));
$application['api']['template']->set("notice", Cloud::$application->language(array('links','notice')));
$application['api']['template']->set("pass", Cloud::$application->language(array('links','pass')));
$application['api']['template']->set("apps", Cloud::$application->language(array('links','apps')));
$application['api']['template']->set("clear", Cloud::$application->language(array('links','clear')));
$application['api']['template']->set("lang", Cloud::$application->language(array('links','lang')));
$application['api']['template']->set("update", Cloud::$application->language(array('links','update')));
$application['api']['template']->set("info", Cloud::$application->language(array('editname')));
$application['api']['template']->set("of", Cloud::$application->language(array('line','of')));
$application['api']['template']->set("aft", Cloud::$application->language(array('line','aft')));
$application['api']['template']->set("updt", Cloud::$application->language(array('updatename')));
// Генерация шаблона
return array
(
	'style' => $application['api']['template']->display('style.css'),
	'html'  => $application['api']['template']->display('style.tmp'),
	'notice' => array('title' => $notice['title'], 'message' => $notice['message'])
);