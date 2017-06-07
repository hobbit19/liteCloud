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
    // Применяем шаблон
    $content .= "
    <a href=\"{$url}\" id=\"block_file\">
    	<img src=\"/{$application['dir']}/images/dir.png\">
    	<div id=\"title_file\">{$objects['dirs'][$i]['name']}</div>
    </a>";
}
// Возвращаем шаблона
return $content;
