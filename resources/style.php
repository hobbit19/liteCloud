<?php
// Создаем заголовок CSS
header("Content-Type: text/css; charset=utf-8");
// Запрос на вывод всех id установленных приложений
$q = mysqli_query(Cloud::$mysqli, "SELECT `id` FROM `apps`");
// Цикл вывода id приложений
while($array = mysqli_fetch_assoc($q))
// Вывод содержимого параметра style
echo Cloud::$application->appoprion(
    $array['id'], 'style', Cloud::$application->init($array['id'], APPLICATION_ID)
);
