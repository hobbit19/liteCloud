<?php
$CONFIG = array
(
	'mysql' => array
	(
		'host'      => 'localhost',
		'username'  => 'YOUR_VALUE_DB_USER',
		'password'  => 'YOUR_VALUE_DB_PASS',
		'dbname'    => 'YOUR_VALUE_DB_NAME'
	),

	'template' => '/resources/templates/',
	'debug' => 1,

	'app_error' => array
	(
		'ru' => array
		(
			0 => '<b>Повреждены файлы приложения.</b>
			Это могло произойти в процессе некорректной установки приложения, или его удаления.
			Советуем вам удалить все записи в реестре о приложении или его файлы.',

			1 => '<b>Приложение небыло установленно.</b>
			Программа запуска приложений не получила содержимого от вызываемого приложения. Попробуйте
			скачать приложение заного и установить его.',

			2 => '<b>Повреждена конфигурация приложения.</b>
			Ключевые данные приложения хранящиеся в реестре были повреждены.
			Советуем вам удалить все записи в реестре о приложении или его файлы.',

			'title' => 'Ошибка приложения'
		),

		'en' => array
		(
			0 => '<b>The application files are corrupted.</b>
			This could happen during the process of incorrect installation of the application, or its removal.
			We advise you to delete all entries in the registry about the application or its files.',

			1 => '<b>The application was not installed.</b>
			The application launcher did not receive content from the called application.
			Try downloading the application and installing it.',

			2 => '<b>The configuration of the application is corrupted.</b>
			The application key data stored in the registry was corrupted.
			We advise you to delete all entries in the registry about the application or its files.',

			'title' => 'Application error'
		)
	),

	'app_button' => array
	(
		'ru' => 'Закрыть',
		'en' => 'Close'
	),

	'mysql_error' => array
	(
		'ru' => 'Невозможно подключиться к бд.',
		'en' => 'Fail connect to database.'
	),

	'path' => 'YOUR_VALUE_WORK_PATH'
);
/*
	Константы проекта
	Назначения: Тип поиска приложений
*/
define('APPLICATION_ID',    0);
define('APPLICATION_DIR',   1);
define('APPLICATION_ALIAS', 2);
/*
	Назначения: Параметры поиска каталога / файла
*/
define('ISDIR',   0);
define('ISFILE',  1);
