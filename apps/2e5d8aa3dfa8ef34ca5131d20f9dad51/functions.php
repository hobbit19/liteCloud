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
		</select><winquery target=\"_self\">" . Cloud::$application->language(array('ok')) . "</winquery>"
	);
	// Окно списка приложений
	else if($type == "applications") return array
	(
		// Название окна
		"title" => Cloud::$application->language(array('links','apps')),
		// Содержимое окна 
		"message" => "<div style=\"padding-bottom:20px;\">Список всех установленных приложений в liteCloud. Вы можете удалять любое приложение кроме тех, кто носит статус Системные.</div>
		<div class=\"appblock_line\">sdf</div>"
	);
}