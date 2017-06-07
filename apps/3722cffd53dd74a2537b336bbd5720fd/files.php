<?php
// Переменная шаблона
$content = NULL;
// Генерируем шаблон файлов
for($i=0;$i<count($objects['files']);$i++)
{
    // Условие следующего шага
    //$step = ($directory[strlen($directory) - 1] == '/') ? $directory : "{$directory}/";
    // Создаем ссылку для перехода к новому каталогу
    //$url = "{$application['api']['urlapp']}&dir={$step}{$objects['dirs'][$i]['name']}";
    // Применяем шаблон
    $content .= "
    <a href=\"\" id=\"block_file\">
    	<img src=\"/{$application['dir']}/images/file.png\">
    	<div id=\"title_file\">{$objects['files'][$i]['name']}</div>
    </a>";
}
// Возвращаем шаблона
return $content;
