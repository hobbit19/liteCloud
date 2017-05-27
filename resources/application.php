<?php
// Если человек не авторизирован - возвращаем отвепт
if(!Cloud::$profile['is_login']) return;
// Получаем информацию о приложении
$array = Cloud::$application->init($_GET['application'], APPLICATION_ID);
// Проверка приложения на целостность
if(count($array) == 0) return Cloud::ajaxerror($CONFIG['app_error'][1]);
else if(!isset($array['config']['name']) && ! isset($array['dir']))
    return Cloud::ajaxerror($CONFIG['app_error'][2]);
else if(!$array['files']) return Cloud::ajaxerror($CONFIG['app_error'][0]);
// Выполнение кода приложения
$app = include "{$_SERVER['DOCUMENT_ROOT']}/{$array['dir']}/code.php";
// Возвращаем ответ
return array(
    'status' => 1,
    'html'   => $app['html'],
    'title'  => $app['title']
);
