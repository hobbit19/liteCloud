<?php
class tempengine
{
	/*
		Назначение переменной: Путь к шаблонам
	*/
	private $_path;
	/*
		Назначение переменной: Путь выбранного шаблона
	*/
	private $_template;
	/*
		Назначение переменной: Массив данных
	*/
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
	public function display($template)
	{
		$this->_template = $this->_path . $template;
		if(!file_exists($this->_template)) die('Шаблона ' . $this->_template . ' не существует!');
		ob_start(); include($this->_template);
		return ob_get_clean();
	}
}
