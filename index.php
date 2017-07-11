﻿<?php
try
{
	// Подключение библиотек и конфигурации
	require_once __DIR__ . "/lib/main.class.php";
	require_once __DIR__ . "/lib/guard.class.php";
	require_once __DIR__ . "/lib/temp.class.php";
	require_once __DIR__ . "/lib/app.class.php";
	require_once __DIR__ . "/lib/system.class.php";
	require_once __DIR__ . "/resources/config.php";
	// Заполнение указателей и проверка бд
	Cloud::handshake($CONFIG);
	if(!Cloud::$mysqli) exit(Cloud::error($CONFIG['mysql_error'][Cloud::$profile['language']]));
	// Вывод контента приложения
	if(isset($_GET['content']) && Cloud::$profile['is_login'])
		exit(json_encode(include __DIR__ . "/resources/application.php"));
	else if (isset($_GET['appstyle']) && Cloud::$profile['is_login'])
	// Вывод таблицы стилей приложений
	exit(include __DIR__ . "/resources/style.php");
	// API для получение файлов
	else if (isset($_GET['get']) && Cloud::$profile['is_login'])
		exit(include __DIR__ . "/resources/filesource.php");
	// Вывод интерфейса заданного контроллера
	$interface = Cloud::__interface();
	// Проверка переадресации
	if($interface['status'] != 1)
		header("Location: {$interface['location']}");
	exit;
}catch (Exception $e)
{
	exit(Cloud::error($e->getMessage()));
}
