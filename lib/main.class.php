<?php
class Cloud
{
	/*
		Назначение переменной: Указатель бд
	*/
	public static $mysqli = false;
	/*
		Назначение переменной: Указатель шаблонизателя
	*/
	public static $template;
	/*
		Назначение переменной: Указатель класса приложений
	*/
	public static $application;
	/*
		Назначение переменной: Данные подключенного пользователя
	*/
	public static $profile = array();
	/*
		Назначение переменной: Указатель на системные функции
	*/
	public static $system;
	/*
		Назначение переменной: Конфигурация системы
	*/
	private static $configuration = array();
	/*
		Назначение функции: Создание указателя бд
		Входящие параметры: Массив данных для подключения
	*/
	private static function mysqlconnect($mysql = array())
	{
		// Проверка на наличае данных для подключения к бд
		if(count($mysql) != 4) return;
		foreach($mysql as $name => $value)
			if(($name == "host" || $name == "username" || $name == "password" || $name == "dbname") && empty($value)) return;
		// Заполнение указателя
		self::$mysqli = mysqli_connect($mysql['host'], $mysql['username'], $mysql['password'], $mysql['dbname']);
		mysqli_set_charset(self::$mysqli, "utf8");
	}
	/*
		Назначение функции: Тип подключенного устройства
		Входящие параметры: Нет
	*/
	public static function handshake($config)
	{
		// Ставим заголовок с кодировкой проекта
		if (!isset($_GET['appstyle']) && !isset($_GET['get']))
			header('Content-Type: text/html; charset=utf-8');
		// Заполнение указателей пользователя, шаблонизатора, базы данны, системы и профиля
		self::mysqlconnect($config['mysql']);
		self::$profile  = self::account(((isset($_COOKIE['id'])) ? $_COOKIE['id'] : 0));
		self::$template = new tempengine($config['template']);
		self::$application = new application(self::$mysqli, $config);
		self::$system   = new system($config['path'], $config);
		// Присвоение конфигураци
		self::$configuration = $config;
	}
	/*
		Назначение функции: Тип подключенного устройства
		Входящие параметры: Нет
	*/
	private static function is_mobile()
	{
		$mobiles = array(
			'iPhone', 'iPod', 'iPad', 'Android', 'webOS', 'BlackBerry', 'Mobile', 'WP10',
			'Symbian', 'Opera M', 'HTC_', 'Fennec/', 'WindowsPhone', 'WP7', 'WP8'
		);
		// Поиск в User Agent совподение с элементами массива
		foreach($mobiles as $mobile)
			if(preg_match("#".$mobile."#i", $_SERVER['HTTP_USER_AGENT'])) return true;
		return false;
	}
	/*
		Назначение функции: Возвращает массив данных пользователя
		Входящие параметры: id пользователя
	*/
	private static function account($id = 0)
	{
		$array = array(
			'is_login'  => false,
			'is_mobile' => self::is_mobile(),
			'language' => 'en'
		);
		// Массив для неавторизированного пользователя
		if(empty($id) || $id == 0 || !Guard::isint($id)) return $array;
		// Извлечение данных из базы и формарование массива
		$query_u = mysqli_query(self::$mysqli, "SELECT `rules`, `login`, `root`, `language`, `name` FROM `users` WHERE `id`='{$id}'");
		if(mysqli_num_rows($query_u) == 1)
		{
			// Перезапись массива
			$user_sql = mysqli_fetch_assoc($query_u);
			$array['is_login'] = true;
			$array['name'] = $user_sql['name'];
			$array['username'] = $user_sql['login'];
			if(($array['root'] = $user_sql['root']) != 1)
				$array['rules'] = (array)json_decode($user_sql['rules'], true);
			// Язык профиля
			$array['language'] = $user_sql['language'];
		}
		// Возвращаем ответ
		return $array;
	}
	/*
		Назначение функции: Возвращает путь к рабочему каталогу
		Входящие параметры: Нету
	*/
	public static function workzone()
	{
		return self::$configuration['path'];
	}
	/*
		Назначение функции: Определение путей и выдачи контента
		Входящие параметры: Нету
	*/
	public static function __interface()
	{
		// Вывод запрашиваемого контроллера
		if (self::$profile['is_login'] && isset($_GET['application']))
		// Выход из текущего профиля
		if ($_GET['application'] == "-1")
		{
			// Убиваем текущие куки
			setcookie("id", NULL);
			// Делаем переадресацию для вывода формы 
			return array('status' => 0, 'location' => '/');
		}else
		{
			// Если человек остался авторизированным
			echo self::$template->display("main_window.tmp");
			return array('status' => 1, 'location' => '');
		}
		// Переадресация на стандартный контроллер при его отсутствии
		else if (self::$profile['is_login'] && !isset($_GET['application'])) return array('status' => 0, 'location' => '/?application=0');
		// Вывод авторизации
		else
		{
			echo self::authorization(); return array('status' => 1, 'location' => '');
		}
	}
	/*
		Назначение функции: Быстрый вывод окна ошибки
		Входящие параметры: Сообщение
	*/
	public static function error($message = "")
	{
		return $message; // Здесь будет кастомный шаблон
	}
	/*
		Назначение функции: Вывод окна ошибки с прогруженным интерфейсом
		Входящие параметры: Сообщение
	*/
	public static function ajaxerror($message = "")
	{
		self::$template->set('message', $message);
		self::$template->set('title', self::$configuration['app_error'][self::$profile['language']]['title']);
		self::$template->set('button', self::$configuration['app_button'][self::$profile['language']]);
		// Выводим статус ошибки и шаблон уведомления
		return array(
			'status' => 0,
			'html'   => self::$template->display('ajaxerror.tmp')
		);
	}
	/*
		Назначение функции: Смена языка пользователя
		Входящие параметры: Язык
	*/
	public static function language($lang = "ru")
	{
		// Проверка на пустую переменную
		if(empty($lang)) return;
		// Очистка переменной
		$lang = Guard::escape($lang);
		// Применяем настройку
		return mysqli_query(
			self::$mysqli, 
			"UPDATE `users` SET `language`='{$lang}' WHERE `login`='" . Cloud::$profile['username'] . "'"
		);
	}
	/*
		Назначение функции: Определение путей и выдачи контента
		Входящие параметры: Массив контроллеров
	*/
	private static function authorization()
	{
		// Проверка на существование пользователя
		if(isset($_POST['login']))
		{
			$username = Guard::escape($_POST['username']);
			$password = Guard::password($_POST['password']);
			// Проверка на существование пользователя
			$query = mysqli_query(
				self::$mysqli, 
				"SELECT `id` FROM `users` WHERE `login`='{$username}' AND `password`='{$password}'"
			);
			if(mysqli_num_rows($query) == 1)
			{
				// Извлечение данных
				$array = mysqli_fetch_assoc($query);
				setcookie("id", $array['id']);
				// Переадресация на страницу приложений
				header('Location: /?application=0');
				exit;
			}
		}
		// Возвращаем html шаблона
		return self::$template->display('login.tmp');
	}
}
