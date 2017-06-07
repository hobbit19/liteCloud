<?php
class application
{
    /*
		Назначение переменной: Массив типов и названий функций
	*/
    private static $types = array(
        0 => 'appid',
        1 => 'appdir',
        2 => 'appalias'
    );
    /*
		Назначение переменной: Указатель на бд
	*/
    private static $mysqli = false;
    /*
		Назначение функции: Получение указателя бд
		Входящие параметры: Указатель бд
	*/
    public function __construct($mysqli_from)
    {
        // Заполнение локального указателя бд
        self::$mysqli = $mysqli_from;
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
            $application['config'] = (array)json_decode($application['config'], true);
        // Если неверный json конфиг возвращаем NULL
        else $application['config'] = NULL;
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
        $path = "{$_SERVER['DOCUMENT_ROOT']}/{$array['dir']}";
        // Возвращаем ответ при существовании всех компонентов
        if(is_dir($path) && file_exists("{$path}/code.php")) return true;
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
        // Возфращаем ответ
        return $apiarray;
    }
}
