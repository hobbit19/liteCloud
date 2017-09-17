<?php
class system
{
	/*
		Назначение переменной: Путь к зоне видимости
	*/
	private static $_path = NULL;
	/*
		Назначение переменной: Конфигурация
	*/
	private static $configuration = array();
	/*
		Назначение переменной: Указатель на базу данных
	*/
	private static $mysqli = false;
	/*
		Назначение переменной: Конфигурация пользователя
	*/
	private static $profile = array();
	/*
		Назначение функции: Получение пути к зоне видимости
		Входящие параметры: Путь
	*/
	public function __construct($fullpath = '', $config = array(), $mysqli, $profile)
	{
		// Запись системных переменных
		self::$_path = $fullpath;
		self::$configuration = $config;
		self::$mysqli = $mysqli;
		self::$profile = $profile;
	}
	/*
		Назначение функции: Создание массива каталогов и файлов
		Входящие параметры: Путь к каталогу
	*/
	public function fromdir($path = '')
	{
		// Если параметр пустой, тогда выходим
		if(empty($path)) return array();
		// Добавление слеша в конце и начале пути
		$path .= ($path[strlen($path) - 1] == '/') ? '' : '/';
		$path = ($path[0] == '/') ? $path : "/{$path}";
		// Выход если каталога не существует
		if(!is_dir(self::$_path . $path)) return array();
		// Определение переменных цикла
		$data 	= scandir(self::$_path . $path);
		$dirs 	= array(); $d = 0; // Переменные каталогов
		$files 	= array(); $f = 0; // Переменные файлов
		// Заполнение массива циклом
		for($i=0;$i<count($data);$i++)
		// Условие на проверку каталога или файла
		if(is_dir(self::$_path . $path . $data[$i]) && $data[$i][0] != ".")
		{
			// Определение массива конкретно взятого каталога
			$dirs[$d] = array(
				'name' => $data[$i], // Имя каталога
				'time' => date("d F Y", filemtime(self::$_path . $path . $data[$i])), // Дата создания каталога
				'rules' => substr(sprintf('%o', fileperms(self::$_path . $path . $data[$i])), -4) // Права каталога
			);
			$d++; // Следующий id каталога
		// Условие, является ли объект файлом
		}else if(!is_dir(self::$_path . $path . $data[$i]) && $data[$i][0] != ".")
		{
			// Получаем информацию о файле
			$about = $this->aboutfile(self::$_path . $path . $data[$i]);
			// Массив для определения типа файла
			$type = explode('.', $data[$i]);
			// Определение массива конкретно взятого файла
			$files[$f] = array(
				'name' => $data[$i], // Имя файла
				'type' => $type[count($type) - 1], // Расширение файла
				'size' => $about['size'], // Размер файла
				'time' => $about['time'] // Дата создания файла
			);
			$f++; // Следующий id файла
		}
		// Возвращаем массив всех каталогов и файлов
		return array('dirs' => $dirs, 'files' => $files);
	}
	/*
		Назначение функции: Расчет веса файла в макс. единице
		Входящие параметры: Путь к файлу/непереведенный вес, тип параметра
	*/
	public function aboutfile($path = '')
	{
		// Массив данных
		$array = array();
		// Поверка на существование файла
		if(!file_exists(self::$_path . $path)) return array();
		// Запись данных в массив
		$array['size'] = $this->sizefile(self::$_path . $path);
		$array['time'] = date("d.m.Y", filemtime(self::$_path . $path));
		$array['rules'] = substr(sprintf('%o', fileperms(self::$_path . $path)), -4);
		// Возвращаем ответ
		return $array;
	}
	/*
		Назначение функции: Расчет свободного места на сервере
		Входящие параметры: Нет
	*/
	public function freespace()
	{
		// Узнаем свободное и общее место
		$free = disk_free_space("/");
		$total = disk_total_space("/");
		// Возвращаем массив
		return array(
			"percent"		=> ($total - $free) * 100 / $total,
			"busyspace"		=> $this->sizefile($total - $free, false),
			"freespace"		=> $this->sizefile($free, false),
			"totalspace"	=> $this->sizefile($total, false)
		);
	}
	/*
		Назначение функции: Расчет веса файла в макс. единице
		Входящие параметры: Путь к файлу/непереведенный вес, тип параметра
	*/
	private function sizefile($file = '', $file_path = true)
	{
		// Если файла нет и стоит положение true Байт
		if($file_path && !file_exists($file)) return "0 Byte";
		// Иначе определяем вес файла и записываем в переменную
		else if($file_path && file_exists($file))
			$filesize = filesize($file);
		// Запись файла в переменную для вычисления веса
		else $filesize = $file;
		// Определение битности
		if($filesize > 1024)
		{
			$filesize = $filesize / 1024;
			// Сравнение пренадлежности веса
			if($filesize > 1024) if(($filesize/1024) > 1024)
			// Возвращаем вес в гигабайтах
			return round((($filesize/1024)/1024), 1) . "Gb";
			// Возвращаем ответ в мегабайтах
			else return round(($filesize/1024), 1) . "Mb";
			// Возвращаем ответ в килобайтах
			else return round($filesize, 1) . "Kb";
		}else
		// Возвращаем ответ в байтах
		return round($filesize, 1)." Byte";
	}
	/*
		Назначение функции: Создание каталога
		Входящие параметры: Путь к каталогу, название
	*/
	public static function make_dir($path = "", $name = "")
	{
		// Список запрещенных символов
		$array = array('.', '"', '/', '\\', '[', ']', ':', ';', '|', '=', ',', '?', '<', '>', '*', '\'', '&');
		// Проверка параметров
		if(empty($path) || empty($name) || !is_dir(self::$_path . $path)) return false;
		// Заменяем запрещенные символы
		for($i=0;$i<count($array);$i++) $name = str_replace($array[$i], "", $name);
		// Проверка пути
		if($path[strlen($path) - 1] != '/') $path = self::$_path . "{$path}/";
		// Создаем каталог
		return mkdir("{$path}{$name}", 0775);
	}
	/*
		Назначение функции: Создание каталога
		Входящие параметры: Путь к каталогу, название
	*/
	public static function userslist($id = -1)
	{
		// Массив пользователей
		$array = array(); $i = 0;
		// Проверка прав пользователя
		if(!self::$profile['root'] || !Guard::isint($id)) return NULL;
		// Извлечение списка всех пользователей
		if($id == -1) $q = mysqli_query(self::$mysqli, "SELECT * FROM `users`");
		// Извлечение конкретного пользователя
		else $q = mysqli_query(self::$mysqli, "SELECT * FROM `users` WHERE `id`='{$id}'");
		// Извлекаем список
		while($rows = mysqli_fetch_assoc($q))
		{
			// Записываем данные пользователя
			$array[$i] = array("login" => $rows['login'], "root" => ($rows['root'] == 1) ? true : false, "avatar" => $rows['avatar'],
			"language" => $rows['language'], "rules" => json_encode($rows['rules'], true), "id" => $rows['id'], "name" => $rows['name']); $i++;
		}// Возвращаем ответ
		return $array;
	}
}
