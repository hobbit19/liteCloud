<?php
# Подключение библиотеки
include("./project.class.php");

# Запросы к базе данных
$q = mysqli_query($_DATABASE, "SELECT `id` FROM `apps`");
$count = mysqli_num_rows($q); $css = '';

# Выводим список установленных приложений
if($count > 0)
	while($rows = mysqli_fetch_assoc($q))
	{
		// Покдлючаем библиотеку приложения и получаем стили
		$APP = Project::app_data($rows['id']);
		$content = include(ROOT_PATH.$APP['dir'].'/code.php');
		if(isset($content['css']))
			$css .= $content['css'];
	}

// Выводим все стили 
echo $css;