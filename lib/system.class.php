<?php
class system
{
	/*
		Variable assignment: Path to the scope
	*/
	private static $_path = NULL;
	/*
		Function assignment: Obtaining the path to the visibility zone
		Incoming parameters: Path
	*/
	public function __construct($fullpath)
	{
		// Write a system variable
		self::$_path = $fullpath;
	}
	/*
		Function assignment: Creating an array of directories and files
		Incoming parameters: Path to the directory
	*/
	public function fromdir($path = '')
	{
		// If the parameter is empty, then we exit
		if(empty($path)) return array();

		// Adding a slash at the end and beginning of the path
		$path .= ($path[strlen($path) - 1] == '/') ? '' : '/';
		$path = ($path[0] == '/') ? $path : "/{$path}";

		// Exit if the directory does not exist
		if(!is_dir(self::$_path . $path)) return array();

		// Defining cycle variables
		$data 	= scandir(self::$_path . $path);
		$dirs 	= array(); $d = 0; // // Directory variables
		$files 	= array(); $f = 0; // File variables

		// Filling an array with a cycle
		for($i=0;$i<count($data);$i++)
		// The condition for checking the directory or file
		if(is_dir(self::$_path . $path . $data[$i]) && $data[$i][0] != ".")
		{
			// The definition of an array of a specific directory
			$dirs[$d] = array(
				'name' => $data[$i], // Directory name
				'time' => date("d F Y", filemtime(self::$_path . $path . $data[$i])), // Catalog creation date
				'rules' => substr(sprintf('%o', fileperms(self::$_path . $path . $data[$i])), -4) // Directory rights
			);
			$d++; // The next directory id
		// The condition whether the object is a file
		}else if(!is_dir(self::$_path . $path . $data[$i]) && $data[$i][0] != ".")
		{
			// Get information about the file
			$about = $this->aboutfile(self::$_path . $path . $data[$i]);

			// Array to determine the type of file
			$type = explode('.', $data[$i]);

			// Definition of an array of a specific file
			$files[$f] = array(
				'name' => $data[$i], // File name
				'type' => $type[count($type) - 1], // File extension
				'size' => $about['size'], // File size
				'time' => $about['time'] // File creation date
			);
			$f++; // Next id of the file
		}
		// Return an array of all directories and files
		return array('dirs' => $dirs, 'files' => $files);
	}
	/*
		Purpose of the function: Calculate the weight of the file in max. Unit
		Incoming parameters: File path / untranslated weight, parameter type
	*/
	public function aboutfile($path = '')
	{
		// Data array
		$array = array();

		// Check for the existence of a file
		if(!file_exists(self::$_path . $path)) return array();

		// Writing data to an array
		$array['size'] = $this->sizefile(self::$_path . $path);
		$array['time'] = date("d.m.Y", filemtime(self::$_path . $path));
		$array['rules'] = substr(sprintf('%o', fileperms(self::$_path . $path)), -4);
		// Return the response
		return $array;
	}
	/*
		Purpose of the function: Calculate the weight of the file in the maximum unit
		Incoming parameters: File path / untranslated weight, parameter type
	*/
	private function sizefile($file = '', $file_path = true)
	{
		// If there is no file and the position is true
		if($file_path && !file_exists($file)) return "0 Байт";

		// Otherwise, we determine the weight of the file and write it to a variable
		else if($file_path && file_exists($file))
			$filesize = filesize($file);

		// Writing a file to a variable to calculate the weight
		else $filesize = $file;
		
		// Determination of bit depth
		if($filesize > 1024)
		{
			$filesize = $filesize / 1024;
			// Weight сomparison
			if($filesize > 1024) if(($filesize/1024) > 1024)
			// We return weight in gigabytes
			return round((($filesize/1024)/1024), 1)." Гб";
			// Return the response in megabytes
			else return round(($filesize/1024), 1)." Mб";
			// Return the response in kilobytes
			else return round($filesize, 1)." Кб";
		}else
		// Return the response in bytes
		return round($filesize, 1)." Байт";
	}
}
