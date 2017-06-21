<?php
/*
    Массив конфигурации проекта
    Содержимое:
    1 - Массив данных для подключения к БД
    2 - Основные контроллеры проекта и их пути
    3 - Путь к html шаблону проекта
    4 - Текущий режим, в котором работает проект
    5 - Массивы ошибок приложений
    8 - Рабочая область действия облака
*/
$CONFIG = array(
    'mysql' => array(
        'host'      => 'localhost',
        'username'  => 'root',
        'password'  => 'pass',
        'dbname'    => 'xcloud_regedit'
    ),

    'controller' => array(
        1 => array(
            'include'   => '/resources/application.php'
        ),

        2 => array(
            'include'   => '/resources/account.php'
        )
    ),

    'template' => '/resources/templates/',
    'debug' => 1,

    'app_error' => array(
        0 => '<b>Повреждены файлы приложения.</b>
        Это могло произойти в процессе некорректной установки приложения, или его удаления.
        Советуем вам удалить все записи в реестре о приложении или его файлы.',

        1 => '<b>Приложение небыло установленно.</b>
        Программа запуска приложений не получила содержимого от вызываемого приложения. Попробуйте
        скачать приложение заного и установить его.',

        2 => '<b>Повреждена конфигурация приложения.</b>
        Ключевые данные приложения хранящиеся в реестре были повреждены.
        Советуем вам удалить все записи в реестре о приложении или его файлы.'
    ),

    'path' => '/home/pi'
);
/*
    Константы проекта
    Назначения: Тип поиска приложений
*/
define('APPLICATION_ID',    0);
define('APPLICATION_DIR',   1);
define('APPLICATION_ALIAS', 2);
/*
    Назначения: Параметры поиска каталога / файла
*/
define('ISDIR',   0);
define('ISFILE',  1);