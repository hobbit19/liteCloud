<?php
// Функция генерации окна
function getwindow($type = "")
{
	// Проверка на пустоту типа
	if(empty($type)) return;
	// Генерация подходящего окна
	if($type == "language") return array
	(
		"title" => Cloud::$application->language(['links','lang']),
		// Содержимое окна 
		"message" => "<div style=\"padding-bottom:20px;\">" . Cloud::$application->language(['info','language']) . "</div>
		<select name=\"language\">
			<option " . ((Cloud::$profile['language'] == "ru") ? "selected" : "") ." value=\"ru\">Russian language (Русский язык)</option>
			<option " . ((Cloud::$profile['language'] == "en") ? "selected" : "") ." value=\"en\">English language (Английский язык)</option>
		</select><winquery target=\"_self\">" . Cloud::$application->language(['ok']) . "</winquery>"
	); // Окно списка приложений
	else if($type == "applications") return array
	(
		"title" => Cloud::$application->language(['links','apps']),
		// Содержимое окна 
		"message" => "<div style=\"padding-bottom:20px;\">Список всех установленных приложений в liteCloud. Вы можете удалять любое приложение кроме тех, кто носит статус Системные.</div>
		<div class=\"appblock_line\">sdf</div>"
	); // Окно списка пользователей
	else if($type == "users")
	{
		$content = NULL;
		// Получаем массив пользователей
		$users = Cloud::$system->userslist();
		// Извлекаем список
		for($i=0;$i<count($users);$i++)
			$content .= "<a href=\"/?application={$_POST['application']}&function=avatar&id={$users[$i]['id']}\" class=\"userlist\">
			<b>{$users[$i]['name']}</b><br>Администратор</a>";
		return array("title" => Cloud::$application->language(['links','users']),
		// Содержимое окна 
		"message" => "<div style=\"padding-bottom:20px;\">Список всех пользователей системы liteCloud. Вы можете редактировать любого пользователя из списка.</div>
		<table><tr><td style=\"width:275px;\"><div class=\"appblock_line scrollbarcustom\">{$content}</div></td><td valign=\"top\"><div class=\"userscontent\">sdsdfsdfsdfsfe</div>
		</td></tr></table>");
	}
}