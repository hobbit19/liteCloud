<?php
class tempengine
{
	/*
		Variable assignment: Path to templates
	*/
	private $_path;
	/*
		Variable assignment: The path of the selected template
	*/
	private $_template;
	/*
		Variable Assignment: Data array
	*/
	private $_var = array();
	/*
		Function assignment: Defining the path to templates
		Incoming parameters: Path to the directory
	*/
	public function __construct($path = '')
	{
		$this->_path = $_SERVER['DOCUMENT_ROOT'] . $path;
	}
	/*
		Function assignment: Assigns the value of a variable
		Incoming parameters: Variable name, value
	*/
	public function set($name, $value)
	{
		$this->_var[$name] = $value;
	}
	/*
		Function assignment: Assign / append the value of the array variable
		Incoming parameters: Variable name, value
	*/
	public function set_cycle($name, $value)
	{
		$this->_var[$name] .= $value;
	}
	/*
		Function assignment: Gets the value of a variable
		Incoming parameters: Variable name
	*/
	public function __get($name)
	{
		if (isset($this->_var[$name])) return $this->_var[$name];
		return '';
	}
	/*
		Function assignment: Gathers a template and displays it on the screen
		Incoming parameters: Template name, response type
	*/
	public function display($template)
	{
		$this->_template = $this->_path . $template;
		if(!file_exists($this->_template)) die('The template ' . $this->_template . ' does not exist!');
		ob_start(); include($this->_template);
		return ob_get_clean();
	}
}
