<?php
class system
{
    /*
		Назначение переменной: Путь к зоне видимости
	*/
    private static $_path = NULL;
    /*
		Назначение функции: Получение пути к зоне видимости
		Входящие параметры: Путь
	*/
    public function __construct($fullpath)
    {
        // Запись системной переменной
        self::$_path = $fullpath;
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
            // Массив для определения типа файла
            $type = explode('.', $data[$i]);
            // Определение массива конкретно взятого файла
            $files[$f] = array(
                'name' => $data[$i], // Имя файла
                'type' => $type[count($type) - 1], // Расширение файла
                'size' => $this->sizefile(self::$_path . $path . $data[$i]), // Размер файла
                'time' => date("d F Y", filemtime(self::$_path . $path . $data[$i])) // Дата создания файла
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
	private function sizefile($file = '', $file_path = true)
	{
        // Если файла нет и стоит положение true
		if($file_path && !file_exists($file)) return "0 Байт";
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
			return round((($filesize/1024)/1024), 1)." Гб";
            // Возвращаем ответ в мегабайтах
			else return round(($filesize/1024), 1)." Mб";
            // Возвращаем ответ в килобайтах
			else return round($filesize, 1)." Кб";
		}else
        // Возвращаем ответ в байтах
        return round($filesize, 1)." Байт";
	}
}
