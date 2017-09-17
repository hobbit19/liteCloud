<?php
// Удаление последнего слеша
if($directory[strlen($directory) - 1] == '/') $directory = substr($directory, 0, -1);
// Переменная ссылки
$urlnow = NULL;
// Если мы в корневом каталоге
if(strlen($directory) == 0) return "<goto left-effect=\"#content_file\" class=\"nxt\" style=\"background:none;\" href=\"{$application['api']['urlapp']}&dir=/\">
<img class=\"dirimg\" src=\"{$application['dir']}/images/mcloud.png\"> liteCloud</goto>";
// Извлекаем объекты
for($i=1;$i<count(($points = explode('/', $directory)));$i++)
// Создаем html для точек перехода
$bc .= "<goto left-effect=\"#content_file\" style=\"" . (($i == count($points) - 1) ? "background:none;" : "")
. "\" class=\"nxt\" href=\"{$application['api']['urlapp']}&dir=" . ($urlnow .= "/{$points[$i]}") . "\">
<img class=\"dirimg\" src=\"{$application['dir']}/images/dir.png\"> {$points[$i]}</goto>";
// Возвращаем содержимое
return "<goto left-effect=\"#content_file\" class=\"nxt\" href=\"{$application['api']['urlapp']}&dir=/\">
<img class=\"dirimg\" src=\"{$application['dir']}/images/mcloud.png\"> liteCloud</goto>{$bc}";