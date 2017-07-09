<p align="center"> <a href="https://github.com/liteCloudRVA" target="_blank"> <img src="https://avatars1.githubusercontent.com/u/30016782?v=3&s=200"> </img> </a> </p>

# Welcome to liteCloud :cloud:

![liteCloud_info](https://github.com/rvasources/media/blob/master/%D0%A1%D0%BD%D0%B8%D0%BC%D0%BE%D0%BA%20%D1%8D%D0%BA%D1%80%D0%B0%D0%BD%D0%B0%20%D0%BE%D1%82%202017-06-07%2014-09-42.png)

[liteCloud](https://github.com/liteCloudRVA/liteCloud) is a user management system for the server (home cloud). This application is suitable for everyone who has their own server and wants to structure their files on it, have easy access to their files from all devices, and write their own additional applications under liteCloud and use them.

# Getting Started

1. The minimum required PHP version 5, installed Apache2/nginx, MySQL.
2. In the local domain folder, clone the liteCloud.

        $ git clone https://github.com/liteCloudRVA/liteCloud

3. Install the database `xcloud_regedit.sql`.
4. Modify the ./resources/config.php file. Enter the data for the database into the `mysql` section, enter the path to the existing directory in the `path` section (within the directory the file manager will work).
5. Go to your local domain, the data for authorization `test:qwerty321`
# Стандартные приложения liteCloud


Изначально предустановлены 4 приложения ( системные )

![liteCloud_menu](https://github.com/rvasources/media/blob/master/%D0%A1%D0%BD%D0%B8%D0%BC%D0%BE%D0%BA%20%D1%8D%D0%BA%D1%80%D0%B0%D0%BD%D0%B0%20%D0%BE%D1%82%202017-06-07%2010-35-14.png)

1) Настройки - Вся информация о системе и базовые настройки системы находятся там.
2) Файлы - Обозреватель файлов и каталогов, реализован полный функционал файлового менеджера.
3) Приложения - В этой категории находятся кастомные приложения системы.
4) Уведомления - Все сообщения системы / приложений будут видны там.

Из всего списка приложений API на редактирование имеют: Приложения, Уведомления. 

# liteCloud API
###

Система имеет несколько основных указателей на стандартные классы проекта, которые представляют собой API системы. Каждый указатель может быть использован только самой системой.
```
1) Cloud::$template - Указатель на класс шаблонизатора.
2) Cloud::$application - Указатель на класс работы с приложениями.
```
Универсальные указатели API, которые могут использоваться как системой, так и кастомными приложениями.
```
1) Cloud::$profile - Массив на профиль авторизированного пользователя.
2) Cloud::$system - Указатель на класс стандартных функций, оптимизированных под систему.
3) $application - Указатель для любого приложения, который запускает API для работы с системой.
```
Web API
```
1) /?content - Используется системой для запуска приложений.
2) /?appstyle - Получает CSS всех установленных приложений.
3) /?get - Скачивание файла в рабочей зоне системы.
```
# Виды приложений в liteCloud
###

Существует 3 типа приложений в liteCloud:
1) Системные - Данный вид приложений отображается в главном меню. Они не редактируются и не заменяются.
2) Кастомные - Устанавливаются в раздел `Приложения` и запускаются как системные приложения.
3) Скрытые - Данные приложения работают в фоновом режиме. Управление приложением происходит в вкладке `Уведомления`.
4) Оконные - Принцип работы как у скрытых приложений, но открывается приложение в окне.

# Ошибки в liteCloud
###

Все ошибки связанные с запусками приложений ( кастомные или стандартные ) выводятся в сплывающее окно, также это окно можно использовать как "Окно уведомлений" внутри приложений через класс для работы с приложениями.

![liteCloud_win](https://github.com/rvasources/media/blob/master/%D0%A1%D0%BD%D0%B8%D0%BC%D0%BE%D0%BA%20%D1%8D%D0%BA%D1%80%D0%B0%D0%BD%D0%B0%20%D0%BE%D1%82%202017-06-26%2009-58-14.png)

Остальные ( системные ошибки ) выводятся вместе с основным HTML-кодом, либо только сама ошибка. Каждая ошибка имеет свой уникальный код.

* Автор приложения: *[RVA](https://github.com/rvasources)*
* Доступные языки: *Русский*.
* Поддерживаемые типы устройств: *Desktop, Mobile, Tablet*.
* Требования к серверу: *Linux, PHP 5+, MySQL, Apache2 / Nginx*.  
* Канал в Telegram: *https://t.me/rva_simon*.  

![liteCloud_files](https://github.com/rvasources/media/blob/master/%D0%A1%D0%BD%D0%B8%D0%BC%D0%BE%D0%BA%20%D1%8D%D0%BA%D1%80%D0%B0%D0%BD%D0%B0%20%D0%BE%D1%82%202017-06-07%2014-20-10.png)
