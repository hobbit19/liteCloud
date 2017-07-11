<?php
class application
{
	/*
		Назначение переменной: Массив типов и названий функций
	*/
	private static $types = array
	(
		0 => 'appid',
		1 => 'appdir',
		2 => 'appalias'
	);
	/*
		Назначение переменной: Указатель на бд
	*/
	private static $mysqli = false;
	/*
		Назначение переменной: Конфигурация облака
	*/
	private static $configuration = array();
	/*
		Назначение переменной: Конфигурация облака
	*/
	private static $fullpath = null;
	/*
		Назначение функции: Получение указателя бд
		Входящие параметры: Указатель бд
	*/
	public function __construct($mysqli_from, $config = array())
	{
		// Заполнение локального указателя бд и конфигурация
		self::$mysqli = $mysqli_from;
		self::$configuration = $config;
	}
	/*
		Назначение функции: Получение информации о приложении
		Входящие параметры: Опознователь, тип
	*/
	public function init($string = "", $type = 0)
	{
		// Проверка существования типа и запуск функции
		if(isset(self::$types[(int)$type]))
		{
			// Вызов функции по входящему индексу
			$function = (string)self::$types[(int)$type];
			return $this->$function(Guard::escape($string));
		}
	}
	/*
		Назначение функции: Получение выходного параметра приложения
		Входящие параметры: id приложения, имя параметра
	*/
	public function appoprion($id = 0, $option = '', $application = array())
	{
		// Проверка входящего параметра
		if(!Guard::isint($id) || empty($option)) return NULL;
		// Запрос на получения пути к приложении и создание массива
		$q = mysqli_query(self::$mysqli, "SELECT `dir` FROM `apps` WHERE `id`='{$id}'");
		// Если приложения не существует
		if(mysqli_num_rows($q) != 1) return NULL;
		$array = mysqli_fetch_array($q);
		// Проверка приложения
		if(!$this->is_app($array)) return NULL;
		// Подключение приложение и возвращаем значение массива
		$app = include "{$_SERVER['DOCUMENT_ROOT']}/{$array['dir']}/code.php";
		if(isset($app[$option])) return $app[$option];
	}
	/*
		Назначение функции: Вывод внутренних уведомлений системы
		Входящие параметры: Название окна, текст сообщения
	*/
	public function notice($title = '', $message = '')
	{
		// Проверка пустых параметров
		if(empty($title) || empty($message)) return NULL;
		// Создание указателя шаблонизатора
		$temp = new tempengine(self::$configuration['template']);
		// Генерация контента
		$temp->set('message', $message);
		$temp->set('title', Guard::escape($title));
		$temp->set('button', self::$configuration['app_button'][Cloud::$profile['language']]);
		// Выводим шаблон уведомления
		return $temp->display('ajaxerror.tmp');
	}
	/*
		Назначение функции: Запись входящих параметров с окна уведомлений
		Входящие параметры: Нет
	*/
	public function winquery()
	{
		// Проверка на наличае входящих параметров
		if(!isset($_POST['winquery']) || !Guard::is_json($_POST['winquery']))
			return array();
		// Запись параметров в массив
		return json_decode($_POST['winquery'], true);
	}
	/*
		Назначение функции: Запись входящих параметров с приложения
		Входящие параметры: Нет
	*/
	public function appquery()
	{
		// Проверка на наличае входящих параметров
		if(!isset($_POST['appquery']) || !Guard::is_json($_POST['appquery']))
			return array();
		// Запись параметров в массив
		return json_decode($_POST['appquery'], true);
	}
	/*
		Назначение функции: Обработка языковых пакетов
		Входящие параметры: Массив цепочки
	*/
	public function language($array = array())
	{
		// Провкарка переменных
		if(self::$fullpath == null || empty(Cloud::$profile['language'])) return false;
		// Полный путь к языковому пакету
		$path = self::$fullpath . "/language.json";
		// Если существует языковой пакета
		if(!file_exists($path)) return false;
		// Извлекаем пакет и проверяем валидность
		if(!Guard::is_json($pack = file_get_contents($path))) return false;
		// Декодируем языковой пакет
		$language = (array)json_decode($pack, true);
		// Идем по языковому списку
		foreach($array as $point)
			// Существует ли массив
			if(is_array($language[$point])) $language = $language[$point];
			// Если нету совпадений
			else return "--";
		// Возвращаем ответ
		return (isset($language[Cloud::$profile['language']]))
		? $language[Cloud::$profile['language']] : "--";
	}
	/*
		Назначение функции: Получение информации о приложении по id
		Входящие параметры: Опознователь
	*/
	private function appid($id = 0)
	{
		// При некорректных данных возвращает данные первого приложения
		if(!Guard::isint($id)) return $this->appfirst();
		// Извлечение данных и проверка на существование
		$query = mysqli_query(self::$mysqli, "SELECT * FROM `apps` WHERE `id`='{$id}'");
		return $this->selectapp($query);
	}
	/*
		Назначение функции: Получение информации о приложении по dir
		Входящие параметры: Опознователь
	*/
	private function appdir($dir = "")
	{
		// Извлечение данных и проверка на существование
		$query = mysqli_query(self::$mysqli, "SELECT * FROM `apps` WHERE `dir`='{$dir}'");
		return $this->selectapp($query);
	}
	/*
		Назначение функции: Получение информации о приложении по alias
		Входящие параметры: Опознователь
	*/
	private function appalias($alias = "")
	{
		// Извлечение данных и проверка на существование
		$query = mysqli_query(self::$mysqli, "SELECT * FROM `apps` WHERE `alias`='{$alias}'");
		return $this->selectapp($query);
	}
	/*
		Назначение функции: Получение информации о первом приложении
		Входящие параметры: Опознователь
	*/
	private function appfirst()
	{
		// Извлечение данных и проверка на существование
		$query = mysqli_query(self::$mysqli, "SELECT * FROM `apps` LIMIT 1");
		return $this->selectapp($query);
	}
	/*
		Назначение функции: Извлечение данных из приложения
		Входящие параметры: Запрос
	*/
	private function selectapp($query)
	{
		// Проверка на существование
		if(mysqli_num_rows($query) != 1) return $this->appfirst();
		// Формирование массива и проверка json конфигурации
		$application = mysqli_fetch_assoc($query);
		if(Guard::is_json($application['config']))
		{
			$application['config'] = (array)json_decode($application['config'], true);
			// Определяем название приложения
			$application['config']['name'] = 
				// Условие проверки варианта для текущего языка
				(isset($application['config']['name'][Cloud::$profile['language']]))
				// Применение названия
				? $application['config']['name'][Cloud::$profile['language']] : "Nameless";
		// Если неверный json конфиг возвращаем NULL
		}else $application['config'] = NULL;
		// Целостность основных файлов
		$application['files'] = ($this->is_app($application)) ? true : false;
		// API для работы с системными функциями
		$application['api'] = $this->api($application);
		return $application;
	}
	/*
		Назначение функции: Проверка целостности файлов
		Входящие параметры: Массив данных приложения
	*/
	private function is_app($array = array())
	{
		self::$fullpath = $path = "{$_SERVER['DOCUMENT_ROOT']}/{$array['dir']}";
		// Возвращаем ответ при существовании всех компонентов
		if(is_dir($path) && file_exists("{$path}/code.php")  && file_exists("{$path}/language.json")) return true;
		return false;
	}
	/*
		Назначение функции: Создание инструментов для работы приложения
		Входящие параметры: Массив данных приложения
	*/
	private function api($array = array())
	{
		$apiarray = array();
		// Системные возможности работы с приложениями
		$apiarray['template'] = new tempengine("/{$array['dir']}/");
		$apiarray['urlapp'] = "/?application={$array['id']}";
		$apiarray['type'] = $array['type'];
		$apiarray['association'] = array();
		// Извлечение ассоциативного массива
		$query = mysqli_query(self::$mysqli, "SELECT * FROM `association`");
		while($rows = mysqli_fetch_assoc($query))
			$apiarray['association'][$rows['type']] = $rows['app_id'];
		// Возвращаем ответ
		return $apiarray;
	}
}
