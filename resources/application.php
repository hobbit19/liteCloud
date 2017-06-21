<?php
// Если человек не авторизирован - возвращаем отвепт
if(!Cloud::$profile['is_login']) return;
// Получаем информацию о приложении
$application = Cloud::$application->init($_POST['application'], APPLICATION_ID);
// Проверка приложения на целостность
if(count($application) == 0) return Cloud::ajaxerror($CONFIG['app_error'][1]);
else if(!isset($application['config']['name']) && ! isset($application['dir']))
    return Cloud::ajaxerror($CONFIG['app_error'][2]);
else if(!$application['files']) return Cloud::ajaxerror($CONFIG['app_error'][0]);
// Выполнение кода приложения
$app = include "{$_SERVER['DOCUMENT_ROOT']}/{$application['dir']}/code.php";
// Переменные для генерации меню
$menu = NULL;
$qapp = mysqli_query(
    Cloud::$mysqli, "SELECT `id`, `config` FROM `apps` WHERE `type`='0'"
);
// Цикл генерации меню
while($rows = mysqli_fetch_assoc($qapp))
{
    // Декодируем конфигурацию приложения
    $appinfo = (array)json_decode($rows['config'], true);
    // Сборка блоков меню для шаблона
	$menu .= "<a href=\"/?application={$rows['id']}\" class=\"appblock"
     // Условие вывода активного приложения
    .(($application['id'] == $rows['id']) ? " active" : "").
    "\">{$appinfo['name']}</a>";
}
// Возвращаем ответ
return array(
    'status' => 1, // Статус корректной работы приложения
    'html'   => $app['html'], // Содержимое приложения
    'title'  => $application['config']['name'], // Имя приложения
    'menu' 	 => $menu, // Измененное меню
    'notice' => Cloud::$application->notice(
        $app['notice']['title'],
        $app['notice']['message']
    ) // Окно уведомлений
);
