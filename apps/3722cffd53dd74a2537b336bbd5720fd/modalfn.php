<?php
// Массив функций программы
$functions = array();
// Если есть запрос на функцию создания
if(isset($_POST['fn']) && $_POST['fn'] == 'create')
{
	// Если есть готовый запрос на создание каталога
	if(!isset($window['dirname']) || empty($window['dirname']))
	{ 
		// Генерация названия окна
		$functions['title'] = Cloud::$application->language(['menu', 'crt']);
		$functions['message'] = "<div><input style=\"width:88%;\" filter-input=\"true\" placeholder=\"" 
		. Cloud::$application->language(['functions', 'create', 'information']) . "\" name=\"dirname\" type=\"text\">
		<winquery target=\"_self\">" . Cloud::$application->language(['functions', 'create', 'button']) . "</winquery></div>";
	// СОздание нового каталога
	} else Cloud::$system->make_dir($directory, $window['dirname']) ;
} // Если есть запрос на функцию загрузки
if(isset($_POST['fn']) && $_POST['fn'] == 'upload')
{
	// Генерация названия окна
	$functions['title'] = Cloud::$application->language(['menu', 'upl']);
	$functions['message'] = "sdfsdf";
}