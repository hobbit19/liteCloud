<?php
class Guard
{
	/*
		Function assignment: Translation of symbols
		Incoming parameters: String
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
		$string = preg_replace(	"/\\\$/"			, "&#036;" 		, $string);
		$string = preg_replace(	"/\r/"				, "" 			, $string);
		$string = str_replace(	"!"					, "&#33;" 		, $string);
		$string = str_replace(	"'"					, "&#39;" 		, $string);
		$string = preg_replace(	"/&amp;#([0-9]+);/s", "&#\\1;" 		, $string);
		if(get_magic_quotes_runtime()) $string = stripslashes($string);
		return $string;
	}
	/*
		Function assignment: Is a parameter of type int
		Incoming parameters: Variable
	*/
	public static function isint($int)
	{
		settype($int, "integer");
		return is_int($int);
	}
	/*
		Function assignment: Is the JSON string object
		Incoming parameters: Json string
	*/
	public static function is_json($string)
	{
		return ((is_string($string) && (is_object(json_decode($string)) || is_array(json_decode($string))))) ? true : false;
	}
	/*
		Function assignment: Removing unnecessary characters
		Incoming parameters: String
	*/
	public function _strip($data)
	{
		$lit = array("\\t", "\\n", "\\n\\r", "\\r\\n", "  ");
		$sp = array('', '', '', '', '');
		return str_replace($lit, $sp, $data);
	}
	/*
		Function assignment: Is the file a picture
		Incoming options: File path
	*/
	public static function is_image($path)
	{
		$typearray = explode('.', $path);
		// Type check
		if ($typearray[count($typearray) - 1] != 'jpg' &&
			$typearray[count($typearray) - 1] != 'png' &&
			$typearray[count($typearray) - 1] != 'jpeg' &&
			$typearray[count($typearray) - 1] != 'gif'
		) return false;
		$is = @getimagesize($path); if(!$is) return false;
		else if(!in_array($is[2], array(1,2,3))) return false;
		else return true;
	}
	/*
		Function assignment: Generating a password hash
		Incoming settings: Password
	*/
	public static function password($data)
	{
		// Deprecated, must be changed
		// // // LIKE (@eorgiose) // // // 
		return md5(strrev(sha1($data)."litecloud".sha1($data))); 
	}
	/*
		Function assignment: Removing unnecessary paths
		Incoming parameters: String
	*/
	public static function slashes($string)
	{
		$nstr = str_replace( "../", 	"", 	$string);
		$nstr = str_replace( "//", 		"", 	$nstr);
		return  str_replace( "./", 		"", 	$nstr);
	}
}
