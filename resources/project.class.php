<?php
# Конфигурация
include($_SERVER['DOCUMENT_ROOT'].'/resources/config.php');
# Указатель на подключение к бд
$_DATABASE 	= Project::mysqli_connect();
/*
	Класс: Project
	В нем находятся базовые функции системы, функции для вызова API.
*/
class Project
{
	/*
		Назначение функции: Подключение к базе данных
		Входящие параметры: Нет
	*/
	public static function mysqli_connect()
	{
		$db = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
		mysqli_set_charset($db, "utf8");
		return $db;
	}
	/*
		Назначение функции: Вывод общей информации системы
		Входящие параметры: Наименование конкретной категории
	*/
	public static function info($tag='')
	{
		$data_db = mysqli_fetch_assoc(mysqli_query(self::mysqli_connect(), "SELECT `login` FROM `users` WHERE `root`='1'"));
		$arr['install']	= (count($data_db) > 0) ? true : false;
		return (empty($tag) || !isset($arr[$tag])) ? $arr : $arr[$tag];
	}
	/*
		Назначение функции: Расчет свободного места на сервере
		Входящие параметры: Путь к рабочему каталогу, тип вывода
	*/
	public static function freespace($dir, $size=true)
	{
		if($size)
			return self::filesize_get(disk_total_space($dir) - disk_free_space($dir), false);
		else
			return substr((disk_free_space($dir) * 100) / disk_total_space($dir), 0, 5);
	}
	/*
		Назначение функции: Запись в массив содержимое каталога
		Входящие параметры: Путь сканируемого каталога
	*/
	public static function scan_dir($path)
	{
		$data 	= scandir($path);
		$dirs 	= array(); $d = 0;
		$files 	= array(); $f = 0;	
		for($i=0;$i<count($data);$i++)
			if(is_dir($path.$data[$i]) && $data[$i] != "." && $data[$i] != ".." && $data[$i][0] != ".")
			{
				$count = scandir($path.$data[$i]); $empt_ct = 0; $d++;
				for($l=0;$l<count($count);$l++)
					if($count[$l] != "." && $count[$l] != "..") $empt_ct++;
				$dirs[$d - 1] = array(
				'name' => $data[$i],'size' => '--','time' => date("d F Y", filemtime($path.$data[$i])),
				'rules' => substr(sprintf('%o', fileperms($path.$data[$i])), -4),'empty' => $empt_ct);
			}elseif(!is_dir($path.$data[$i]) && $data[$i] != "." && $data[$i] != ".." && $data[$i][0] != ".")
			{
				$type = explode('.', $data[$i]); $f++;
				$files[$f - 1] = array(
				'name' => $data[$i], 'type' => $type[count($type)-1], 'size' => self::filesize_get($path.$data[$i]), 
				'time' => date("d F Y", filemtime($path.$data[$i])));
			}
		return array('dirs' => $dirs, 'files' => $files);
	}
	/*
		Назначение функции: Информация о файле/каталоге
		Входящие параметры: Путь к файле/каталоге
	*/
	public static function about_doc($way = '')
	{
		if(empty($way)) return array('type' => 0);
		if(is_dir($way))
		{
			$count = scandir($way); $empt_ct = 0;
			for($l=0;$l<count($count);$l++)
				if($count[$l] != "." && $count[$l] != "..") $empt_ct++;
			return array(
			'type' => 1, 'size' => '--', 'time' => date("d F Y", filemtime($way)),
			'rules' => substr(sprintf('%o', fileperms($way)), -4),'empty' => $empt_ct);
		}elseif(is_file($way))
			return array(
			'type' => 2,'size' => self::filesize_get($way),
			'rules' => substr(sprintf('%o', fileperms($way)), -4),
			'time' => date("d F Y", filemtime($way)));
		else return array('type' => 3);
	}
	/*
		Назначение функции: Расчет веса файла в макс. единице
		Входящие параметры: Путь к файлу/непереведенный вес, тип параметра
	*/
	private static function filesize_get($file, $file_path=true)
	{
		if($file_path && !file_exists($file)) return "0 Байт";
		elseif($file_path && file_exists($file))
			$filesize = filesize($file);
		else $filesize = $file;
		if($filesize > 1024)
		{
			$filesize = ($filesize/1024);
			if($filesize > 1024)
				if(($filesize/1024) > 1024) 
					return round((($filesize/1024)/1024), 1)." ГБ";   
				else return round(($filesize/1024), 1)." MБ";   
			else return round($filesize, 1)." КБ";   
		}else return round($filesize, 1)." Байт";   
	}
	/*
		Назначение функции: Тип подключенного устройства
		Входящие параметры: Нет
	*/
	private static function is_mobile()
	{
		$mobiles = array(
			'iPhone', 'iPod', 'iPad', 'Android', 'webOS', 'BlackBerry', 'Mobile', 
			'Symbian', 'Opera M', 'HTC_', 'Fennec/', 'WindowsPhone', 'WP7', 'WP8', 'WP10'
		);
		foreach($mobiles as $mobile)
			if(preg_match("#".$mobile."#i", $_SERVER['HTTP_USER_AGENT']))
				return true;
		return false;
	}
	/*
		Назначение функции: Информация об учетной записи
		Входящие параметры: id пользователя
	*/
	public static function user($id=0)
	{
		if(!empty($id) && self::isint($id) && $id > 0)
		{
			$data = mysqli_fetch_assoc(mysqli_query(self::mysqli_connect(), "SELECT * FROM `users` WHERE `id`='{$id}'"));
			return array(
				'rules' => json_decode($data['rules']), 'root' => $data['root'], 'login' => $data['login'], 
				'avatar' => $data['avatar'], 'mobile' => self::is_mobile()
			);
		}else return array();
	}
	/*
		Назначение функции: Вывод конфигурации приложения
		Входящие параметры: id приложения
	*/
	public static function appinfo($id)
	{
		if(!self::isint($id)) return;
		$q = mysqli_fetch_assoc(mysqli_query(self::mysqli_connect(), "SELECT `config` FROM `apps` WHERE `id`='{$id}'"));
		return (!empty($q['config']) && self::is_json($q['config'])) ? (array)json_decode($q['config'], true) : array('name' => 'Безымянный', 'description' => 'Без описания.');
	}
	/*
		Назначение функции: API для подключаемых приложений и массива ассоциаций
		Входящие параметры: id приложения
	*/
	public static function app_data($id)
	{
		if(!self::isint($id)) return; $arr = array();
		$q = mysqli_query(self::mysqli_connect(), "SELECT * FROM `apps` WHERE `id`='{$id}'");
		if(mysqli_num_rows($q) != 1) return $arr['isset_sql'] = false;
		$data = mysqli_fetch_assoc($q);
		$arr['dir']			= '/'.$data['dir'];
		$arr['tmp']			= new Temp($arr['dir'].'/');
		$arr['isset_dir']	= is_dir(ROOT_PATH.$arr['dir']);
		$arr['isset_code']	= file_exists(ROOT_PATH.$arr['dir'].'/code.php');
		$arr['isset_sql']	= true;
		$arr['url_app']		= '/?path=home&app='.$id;
		$arr['association']	= array();
		$arr['type']		= $data['type'];
		$astn = mysqli_query(self::mysqli_connect(), "SELECT * FROM `association`"); 
		while($rows = mysqli_fetch_assoc($astn))
			$arr['association'][$rows['type']] = $rows['app_id'];
		return $arr;
	}
	/*
		Назначение функции: Генерация хэша пароля
		Входящие параметры: Пароль
	*/
	public static function password($data)
	{
		return md5(strrev(sha1($data)."Quareal_xCloud_Project".sha1($data)));
	}
	/*
		Назначение функции: Определение полного пути
		Входящие параметры: Входящий путь
	*/
	public static function fullpath($way)
	{
		$full = self::get_config();
		$nway = self::spashes_replace($way);
		
		if($nway[0] == "/")
			return (is_file($full['home_path'] . $nway) || is_dir($full['home_path'] . $nway)) ? $full['home_path'] . $nway : '/';
		else 
			return (is_file($full['home_path'] . "/" . $nway) || is_dir($full['home_path'] . "/" . $nway)) ? $full['home_path'] . "/" . $nway : '/';
	}
	/*
		Назначение функции: Массив конфигурации xCloud
		Входящие параметры: Нет
	*/
	public static function get_config()
	{
		$data = mysqli_fetch_assoc(mysqli_query(self::mysqli_connect(), "SELECT * FROM `settings`"));
		return (array)json_decode($data['json_config']);
	}
	/*
		Назначение функции: Перевод символов
		Входящие параметры: Строка
	*/
	public static function escape($string)
	{
		$string = str_replace(	"&#032;"			, " " 			, $string);
		$string = str_replace(	"&"					, "&amp;" 		, $string);
		$string = str_replace(	"<!--"				, "&#60;&#33;--", $string);
		$string = str_replace(	"-->"				, "--&#62;" 	, $string);
		$string = preg_replace(	"/<script/i"		, "&#60;script"	, $string);
		$string = str_replace(	">"					, "&gt;" 		, $string);
		$string = str_replace(	"<"					, "&lt;" 		, $string);
		$string = str_replace(	"\""				, "&quot;" 		, $string);
		$string = str_replace(	"\&quot;"			, "&quot;" 		, $string);
		$string = str_replace(	"\'"				, "&#39;" 		, $string);
		$string = preg_replace(	"/\n/"				, "<br />" 		, $string); 
		$string = preg_replace( "/\\\$/"			, "&#036;" 		, $string);
		$string = preg_replace( "/\r/"				, "" 			, $string);
		$string = str_replace(	"!"					, "&#33;" 		, $string);
		$string = str_replace(	"'"					, "&#39;" 		, $string); 
		$string = preg_replace(	"/&amp;#([0-9]+);/s", "&#\\1;" 		, $string);
		if(get_magic_quotes_runtime()) $string = stripslashes($string);
		return $string;
	}

	public static function spashes_replace($string)
	{
		$nstr = str_replace( "../", 	"", 	$string);
		$nstr = str_replace( "//", 		"", 	$nstr);
		return  str_replace( "./", 		"", 	$nstr);
	}
	/*
		Назначение функции: Является ли файл изображением
		Входящие параметры: Путь к файлу
	*/
	public static function is_image($path) 
	{
		$is = @getimagesize($path); if(!$is) return false;
		elseif(!in_array($is[2], array(1,2,3))) return false;
		else return true;
	}
	/*
		Назначение функции: Ссылка на xCloud
		Входящие параметры: Нет
	*/
	public static function url()
	{
		return (($_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://').$_SERVER['HTTP_HOST'];
	}
	/*
		Назначение функции: Является ли параметр типом int
		Входящие параметры: Переменная
	*/
	public static function isint($int)
	{
		settype($int, "integer");
		return is_int($int);
	}
	/*
		Назначение функции: Является ли строка JSON объектом
		Входящие параметры: Json строка
	*/
	private static function is_json($string)
	{
		return ((is_string($string) && (is_object(json_decode($string)) || is_array(json_decode($string))))) ? true : false;
	}
	/*
		Назначение функции: Поиск id приложения по алиасу
		Входящие параметры: Алиас приложения
	*/
	public static function app_alias($alias='')
	{
		$aldb = self::escape($alias);
		if(empty($aldb)) return;
		return mysqli_fetch_assoc(mysqli_query(self::mysqli_connect(), "SELECT `id` FROM `apps` WHERE `alias`='{$aldb}'"));
	}
	/*
		Назначение функции: Определение положения файла/папки
		Входящие параметры: Путь, тип
	*/
	public static function doc_way($way, $type=0)
	{
		$config = self::get_config();
		$_way = str_replace('../', '', urldecode($way));
		$file_d = $config['home_path'].(($_way[0] == '/') ? $_way : '/'.$_way);
		$file_e = $config['home_path'].(($way[0] == '/') ? $way : '/'.$way);
		return ($type == 0) ? ((is_dir($file_d)) ? $file_d : $file_e) : ((file_exists($file_d)) ? $file_d : $file_e);
	}
	/*
		Назначение функции: Добавление записей в уведомления
		Входящие параметры: id приложения, сообщение
	*/
	public static function notice_add($id, $message)
	{
		if(!self::isint($id) || $id <= 0) return false;
		$data = mysqli_fetch_assoc(mysqli_query(self::mysqli_connect(), "SELECT `dir`, `config` FROM `apps` WHERE `id`='{$id}'"));
		if(count($data) < 1) return false;
		if(mysqli_query(self::mysqli_connect(), "INSERT INTO `notices` (`id`, `dir`, `content`) VALUES (NULL, '{$data['dir']}', '".htmlspecialchars($message)."')"))
			return true;
		else return false;
	}
	/*
		Назначение функции: Добавление записей в уведомления (системные уведомления)
		Входящие параметры: Сообщение
	*/
	private static function notice_sys($message)
	{
		if(mysqli_query(self::mysqli_connect(), "INSERT INTO `notices` (`id`, `dir`, `content`) VALUES (NULL, NULL, '".htmlspecialchars($message)."')"))
			return true;
		else return false;
	}
}
/*
	Класс: Temp
	Служит для сборки шаблона из переменных из отдельных файлов.
*/
class Temp
{
	private $_path;
	private $_template;
	private $_var = array();
	/*
		Назначение функции: Определения пути к шаблонам
		Входящие параметры: Путь к каталогу
	*/
	public function __construct($path = '')
	{
		$this->_path = $_SERVER['DOCUMENT_ROOT'] . $path;
	}
	/*
		Назначение функции: Присваивает значение переменной
		Входящие параметры: Имя переменной, значение
	*/
	public function set($name, $value)
	{
		$this->_var[$name] = $value;
	}
	/*
		Назначение функции: Присваивает/дополняет значение переменной массива
		Входящие параметры: Имя переменной, значение
	*/
	public function set_cycle($name, $value)
	{
		$this->_var[$name] .= $value;
	}
	/*
		Назначение функции: Получает значение переменной
		Входящие параметры: Имя переменной
	*/
	public function __get($name)
	{
		if (isset($this->_var[$name])) return $this->_var[$name];
		return '';
	}
	/*
		Назначение функции: Собирает шаблон и выводит его на экран
		Входящие параметры: Имя шаблона, тип ответа
	*/
	public function display($template, $return = false)
	{
		$this->_template = $this->_path . $template;
		if(!file_exists($this->_template)) die('Шаблона ' . $this->_template . ' не существует!');
		ob_start(); include($this->_template);
		if($return) return ob_get_clean();
		else echo ob_get_clean();
	}
	/*
		Назначение функции: Удаление лишних спецсимволов
		Входящие параметры: Строка
	*/
	private function _strip($data)
	{
		$lit = array("\\t", "\\n", "\\n\\r", "\\r\\n", "  ");
		$sp = array('', '', '', '', '');
		return str_replace($lit, $sp, $data);
	}
	/*
		Назначение функции: Защита от XSS
		Входящие параметры: Строка
	*/
	public function xss($data)
	{
		if(is_array($data)) 
		{
			$escaped = array();
			foreach ($data as $key => $value) 
				$escaped[$key] = $this->xss($value);
			return $escaped;
		}
		return htmlspecialchars($data, ENT_QUOTES);
	}
}