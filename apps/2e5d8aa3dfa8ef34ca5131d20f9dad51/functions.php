<?php
// Функция генерации окна
function getwindow($type = "")
{
	// Проверка на пустоту типа
	if(empty($type)) return;
	// Генерация подходящего окна
	if($type == "language") return array
	(
		// Название окна
		"title" => Cloud::$application->language(array('links','lang')),
		// Содержимое окна 
		"message" => "<div style=\"padding-bottom:20px;\">" . Cloud::$application->language(array('info','language')) . "</div>
		<select name=\"language\">
			<option " . ((Cloud::$profile['language'] == "ru") ? "selected" : "") ." value=\"ru\">Russian language (Русский язык)</option>
			<option " . ((Cloud::$profile['language'] == "en") ? "selected" : "") ." value=\"en\">English language (Английский язык)</option>
		</select><winquery>" . Cloud::$application->language(array('ok')) . "</winquery>"
	);
}