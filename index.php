<?php
try
{
	// Подключение библиотек и конфигурации
	require_once __DIR__ . "/lib/main.class.php";
	require_once __DIR__ . "/lib/guard.class.php";
	require_once __DIR__ . "/lib/temp.class.php";
	require_once __DIR__ . "/lib/app.class.php";
	require_once __DIR__ . "/resources/config.php";
	// Заполнение указателей и проверка бд
	Cloud::handshake($CONFIG);
	if(!Cloud::$mysqli) exit(Cloud::error("Невозможно подключиться к бд."));
	// Вывод контента приложения
	if(isset($_GET['content']) && Cloud::$profile['is_login'])
	{
		// Подключение и кодирования управляющего скрипта
		$application = include __DIR__ . "/resources/application.php";
		exit(json_encode($application));
	}
	// Вывод интерфейса заданного контроллера
	$interface = Cloud::interface_get($CONFIG['controller']);
	// Проверка переадресации
	if($interface['status'] != 1)
		header('Location: ' . $interface['location']);
	exit;
}catch (Exception $e)
{
	exit(Cloud::error($e->getMessage()));
}
