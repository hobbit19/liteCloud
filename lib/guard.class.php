<?php
class Guard
{
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
	public static function is_json($string)
	{
		return ((is_string($string) && (is_object(json_decode($string)) || is_array(json_decode($string))))) ? true : false;
	}
    /*
        Назначение функции: Удаление лишних спецсимволов
        Входящие параметры: Строка
    */
    public function _strip($data)
	{
		$lit = array("\\t", "\\n", "\\n\\r", "\\r\\n", "  ");
		$sp = array('', '', '', '', '');
		return str_replace($lit, $sp, $data);
	}
    /*
		Назначение функции: Является ли файл изображением
		Входящие параметры: Путь к файлу
	*/
	public static function is_image($path)
	{
		$is = @getimagesize($path); if(!$is) return false;
		else if(!in_array($is[2], array(1,2,3))) return false;
		else return true;
	}
    /*
		Назначение функции: Генерация хэша пароля
		Входящие параметры: Пароль
	*/
	public static function password($data)
	{
        // Устарело, надо менять
		return md5(strrev(sha1($data)."litecloud".sha1($data)));
	}
}
