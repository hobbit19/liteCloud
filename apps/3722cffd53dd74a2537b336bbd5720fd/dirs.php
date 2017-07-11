<?php
// Переменная шаблона
$content = NULL;
// Генерируем шаблон каталогов
for($i=0;$i<count($objects['dirs']);$i++)
{
	// Условие следующего шага
	$step = ($directory[strlen($directory) - 1] == '/') ? $directory : "{$directory}/";
	// Создаем ссылку для перехода к новому каталогу
	$url = "{$application['api']['urlapp']}&dir={$step}{$objects['dirs'][$i]['name']}";
	// Создаем урезанное имя каталога
	$name = (strlen($objects['dirs'][$i]['name']) > 16) ?
		mb_substr($objects['dirs'][$i]['name'], 0, 16, 'UTF-8') . ".." : $objects['dirs'][$i]['name'];
	// Применяем шаблон
	$content .= "
	<a href=\"{$url}\" id=\"block_file\">
		<p><img src=\"/{$application['dir']}/images/dir.png\"></p>
		<div id=\"title_file\">{$name}</div>
	</a>";
}
// Возвращаем шаблона
return $content;
