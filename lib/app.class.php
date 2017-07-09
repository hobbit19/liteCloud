<?php
class application
{
	/*
		Variable assignment: An array of types and function names
	*/
	private static $types = array(
		0 => 'appid',
		1 => 'appdir',
		2 => 'appalias'
	);
	/*
		Variable assignment: Pointer to the database
	*/
	private static $mysqli = false;
	/*
		Variable assignment: Cloud configuration
	*/
	private static $configuration = array();
	/*
		Function assignment: Obtaining a pointer to the database
		Incoming parameters: Pointer to the database
	*/
	public function __construct($mysqli_from, $config = array())
	{
		// Populating the local pointer and configuration
		self::$mysqli = $mysqli_from;
		self::$configuration = $config;
	}
	/*
		Purpose of the function: Getting information about the application
		Incoming parameters: Identifier, type
	*/
	public function init($string = "", $type = 0)
	{
		// Checking the existence of a type and starting a function
		if(isset(self::$types[(int)$type]))
		{
			// Calling a function on an incoming index
			$function = (string)self::$types[(int)$type];
			return $this->$function(Guard::escape($string));
		}
	}
	/*
		Function assignment: Getting the output parameter of the application
		Incoming parameters: Application id, parameter name
	*/
	public function appoprion($id = 0, $option = '', $application = array())
	{
		// Checking the input parameter
		if(!Guard::isint($id) || empty($option)) return NULL;

		// Request to get the path to the application and create an array
		$q = mysqli_query(self::$mysqli, "SELECT `dir` FROM `apps` WHERE `id`='{$id}'");

		// If the application does not exist
		if(mysqli_num_rows($q) != 1) return NULL;
		$array = mysqli_fetch_array($q);

		// Application verification
		if(!$this->is_app($array)) return NULL;

		// Connect the application and return the value of the array
		$app = include "{$_SERVER['DOCUMENT_ROOT']}/{$array['dir']}/code.php";
		if(isset($app[$option])) return $app[$option];
	}
	/*
		Function assignment: Displaying internal system notifications
		Incoming parameters: Window name, message text
	*/
	public function notice($title = '', $message = '')
	{
		// Checking for empty parameters
		if(empty($title) || empty($message)) return NULL;

		// Creating the template pointer
		$temp = new tempengine(self::$configuration['template']);

		// Content generation
		$temp->set('message', $message);
		$temp->set('title', Guard::escape($title));

		// Display the notification template
		return $temp->display('ajaxerror.tmp');
	}
	/*
		Function: Record incoming parameters from the notification window
		Incoming parameters: None
	*/
	public function winquery()
	{
		// Checking for incoming parameters
		if(!isset($_POST['winquery']) || !Guard::is_json($_POST['winquery']))
			return array();

		// Writing parameters to an array
		return json_decode($_POST['winquery'], true);
	}
	/*
		Function assignment: Recording of incoming parameters from the application
		Incoming parameters: None
	*/
	public function appquery()
	{
		// Checking for incoming parameters
		if(!isset($_POST['appquery']) || !Guard::is_json($_POST['appquery']))
			return array();

		// Writing parameters to an array
		return json_decode($_POST['appquery'], true);
	}
	/*
		Purpose of the function: Obtaining information about the application by id
		Incoming parameters: Identifier
	*/
	private function appid($id = 0)
	{
		// If the data is incorrect, it returns the data of the first application
		if(!Guard::isint($id)) return $this->appfirst();

		// Extracting data and checking for existence
		$query = mysqli_query(self::$mysqli, "SELECT * FROM `apps` WHERE `id`='{$id}'");
		return $this->selectapp($query);
	}
	/*
		Purpose of the function: Obtaining information about the application by dir
		Incoming parameters: Identifier
	*/
	private function appdir($dir = "")
	{
		// Extracting data and checking for existence
		$query = mysqli_query(self::$mysqli, "SELECT * FROM `apps` WHERE `dir`='{$dir}'");
		return $this->selectapp($query);
	}
	/*
		Purpose of the function: Obtaining information about the application by alias
		Incoming parameters: Identifier
	*/
	private function appalias($alias = "")
	{
		// Extracting data and checking for existence
		$query = mysqli_query(self::$mysqli, "SELECT * FROM `apps` WHERE `alias`='{$alias}'");
		return $this->selectapp($query);
	}
	/*
		Purpose of the function: Getting information about the first application
		Incoming parameters: Identifier
	*/
	private function appfirst()
	{
		// Extracting data and checking for existence
		$query = mysqli_query(self::$mysqli, "SELECT * FROM `apps` LIMIT 1");
		return $this->selectapp($query);
	}
	/*
		Function: Extracting data from the application
		Incoming parameters: Request
	*/
	private function selectapp($query)
	{
		// Check for existence
		if(mysqli_num_rows($query) != 1) return $this->appfirst();

		// Forming an array and checking the json configuration
		$application = mysqli_fetch_assoc($query);
		if(Guard::is_json($application['config']))
			$application['config'] = (array)json_decode($application['config'], true);

		// If the wrong json config is returned NULL
		else $application['config'] = NULL;

		// Integrity of the main files
		$application['files'] = ($this->is_app($application)) ? true : false;

		// API for working with system functions
		$application['api'] = $this->api($application);
		return $application;
	}
	/*
		Function: Checking the integrity of files
		Incoming parameters: An array of application data
	*/
	private function is_app($array = array())
	{
		$path = "{$_SERVER['DOCUMENT_ROOT']}/{$array['dir']}";

		// Return the answer if there are all the components
		if(is_dir($path) && file_exists("{$path}/code.php")) return true;
		return false;
	}
	/*
		Function: Creating tools for running the application
		Incoming parameters: An array of application data
	*/
	private function api($array = array())
	{
		$apiarray = array();

		// System capabilities for working with applications
		$apiarray['template'] = new tempengine("/{$array['dir']}/");
		$apiarray['urlapp'] = "/?application={$array['id']}";
		$apiarray['type'] = $array['type'];
		$apiarray['association'] = array();

		// Extracting an associative array
		$query = mysqli_query(self::$mysqli, "SELECT * FROM `association`");
		while($rows = mysqli_fetch_assoc($query))
			$apiarray['association'][$rows['type']] = $rows['app_id'];
		
		// Returning the response
		return $apiarray;
	}
}
