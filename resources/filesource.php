<?php
// Проверка существования записи пути конфигурации
if (!isset($CONFIG['path']) || !is_dir($CONFIG['path'])) return NULL;
// Проверка получения входящего параметра
if (!isset($_GET['get']) || empty($_GET['get'])) return NULL;
// Получение реального пути файла
$file = Guard::slashes(urldecode($_GET['get']));
// Коррекция выходного пути
$file = ($file[0] == '/') ? $CONFIG['path'] . $file : "{$CONFIG['path']}/{$file}";
// Проверка на существование файла
if(!file_exists($file)) return NULL;
// Очистка кэша
if(ob_get_level()) ob_end_clean();
// Выдача заголовков
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream'); // Стримовая передача
header('Content-Disposition: attachment; filename=' . basename($file)); // Выходное имя
header('Content-Transfer-Encoding: binary'); // Тип кодирования
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($file)); // Длина содержимого
// Отдача файла
readfile($file);
