<?php
/*
	Приложение с выводом общей информации системы
*/
$space_procent 	= Project::freespace($_SETTINGS['home_path'], false);
$space_size 	= Project::freespace($_SETTINGS['home_path'], true);

