<?php
// Определение выходных свойств файла
function fileinfo($fullpath = '', $finename = '', $appurl = '', $dir = '')
{
	// Если пустая переменная
	if(empty($fullpath) || empty($finename) || empty($appurl) || empty($dir) ||
		!file_exists(Cloud::workzone() . "{$fullpath}{$finename}")) return array();
	// Генерация массива по типу
	if(Guard::is_image(Cloud::workzone() . "{$fullpath}{$finename}"))
	return array(
		"image"	=> "<img src=\"/?get={$fullpath}{$finename}\" style=\"border-radius:5px;\">",
		"href"	=> "",
		"html"	=> ""
	);
	// Если не прошло условие
	else return array(
		"href"	=> "{$appurl}&file={$finename}&dir={$fullpath}",
		"html"	=> "",
		"image"	=> "<img src=\"/{$dir}/images/file.png\">"
	);
}
// Окно информации файла
function filewindow($fullpath = '', $filename = '', $appurl = '', $dir = '')
{
	// Получаем информацию о файле
	$about = Cloud::$system->aboutfile("{$fullpath}{$filename}");
	// Генерация имени окна
	$title = (strlen($filename) > 32) ?
		mb_substr(Guard::escape($filename), 0, 32, 'UTF-8') . ".." : Guard::escape($filename);
	// Собираем информацию о файле
	$info = fileinfo($fullpath, $filename, $appurl, $dir);
	// Возвращаем контент окна
	return array('title' => Guard::escape($filename), 'message' => '
	<table id="actionstable"><tr>
	<td class="left">' . $info['image'] . '</td>
	<td class="right"><div>
	<input filter-input="true" style="width:363px;" placeholder="' . Cloud::$application->language(array('name')) . '" value="' . $filename . '" type="text" name="kkkk">
	<winquery>' . Cloud::$application->language(array('rnm')) . '</winquery></div><table id="tableinwin">
	<tr><td valign="top" class="left_text">' . Cloud::$application->language(array('date')) . '</td>
	<td>' . $about['time'] . '</td></tr><tr><td valign="top" class="left_text">' . Cloud::$application->language(array('size')) . '</td>
	<td>' . $about['size'] . '</td></tr><tr><td valign="top" class="left_text">' . Cloud::$application->language(array('rule')) . '</td>
	<td>' . $about['rules'] . '</td></tr></table></td></tr></table>');
}
