<?php
// Массив уведомлений
$notice = array();
// Условие для _GET параметра file
if(isset($_POST['file']) && !empty($_POST['file']))
    $notice = filewindow($_POST['dir'], $_POST['file'], $application['api']['urlapp'], $application['dir']);
// Переменная шаблона
$content = NULL;
// Генерируем шаблон файлов
for($i=0;$i<count($objects['files']);$i++)
{
    // Условие следующего шага
    $step = ($directory[strlen($directory) - 1] == '/') ? $directory : "{$directory}/";
    // Определение массива для файла
    $file = fileinfo($step, $objects['files'][$i]['name'], $application['api']['urlapp'], $application['dir']);
    // Создаем урезанное имя файла
    $name = (strlen($objects['files'][$i]['name']) > 13) ?
        mb_substr($objects['files'][$i]['name'], 0, 13, 'UTF-8') . ".." : $objects['files'][$i]['name'];
    // Применяем шаблон
    $content .= "
    <a {$file['html']} href=\"{$file['href']}\" id=\"block_file\">
    	<p>{$file['image']}</p>
    	<div id=\"title_file\">{$name}</div>
    </a>";
}
// Возвращаем шаблона
return array('content' => $content, 'notice' => $notice);
