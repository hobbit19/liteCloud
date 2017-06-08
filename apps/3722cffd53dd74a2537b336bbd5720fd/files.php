<?php
// Переменная шаблона
$content = NULL;
// Генерируем шаблон файлов
for($i=0;$i<count($objects['files']);$i++)
{
    // Условие следующего шага
    $step = ($directory[strlen($directory) - 1] == '/') ? $directory : "{$directory}/";
    // Определение иконки для файла
    if(Guard::is_image(Cloud::workzone() . "/{$step}{$objects['files'][$i]['name']}"))
    // Отображение картинки если объект является картинкой
    $image = "<img src=\"/?get={$step}{$objects['files'][$i]['name']}\" style=\"border-radius:5px;\">";
    // Отображение неизвестного объекта
    else $image = "<img src=\"/{$application['dir']}/images/file.png\">";
    // Создаем урезанное имя файла
    $name = (strlen($objects['files'][$i]['name']) > 13) ?
        mb_substr($objects['files'][$i]['name'], 0, 13, 'UTF-8') . ".." : $objects['files'][$i]['name'];
    // Применяем шаблон
    $content .= "
    <a href=\"/?get={$step}{$objects['files'][$i]['name']}\" id=\"block_file\">
    	<p>{$image}</p>
    	<div id=\"title_file\">{$name}</div>
    </a>";
}
// Возвращаем шаблона
return $content;
