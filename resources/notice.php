<?php
# Получаем запрос на удаление, если его нету - выводим контент
if(isset($_POST['notice']) && !empty($_POST['notice']))
{
	# Защита от SQL Injection
	if(Project::isint($_POST['notice']) && (int)$_POST['notice'] > 0)
	# Удаление записи
	mysqli_query($_DATABASE, "DELETE FROM `notices` WHERE `id`='{$_POST['notice']}'");
}else
{
	$html = '';
	# Вывод мини приложения в уведомления
	if(isset($_COOKIE['miniapp']) && !empty($_COOKIE['miniapp']))
	{
		# Декодирование контента приложения
		$array = json_decode($_COOKIE['miniapp']);
		$tmp->set('content', $array->content);
		# Вывод контента
		$html .= $tmp->display('miniapp.tmp', true);
	}

	# Получение списка уведомлений
	$q = mysqli_query($_DATABASE, "SELECT * FROM `notices`");
	# Сравнение количества записей 
	if(mysqli_num_rows($q) > 0)
	{
		# Получение блоков уведомлений
		while($rows = mysqli_fetch_assoc($q))
			$html .= "
			<div id=\"notice_{$rows['id']}\" class=\"notice_block\"><table><tr>
				<td style=\"width:74px;\" valign=\"top\">
					<img src=\"".(empty($rows['dir']) ? "/resources/assets/img/sys_notice.png" : "")."\">
				</td>
				<td valign=\"top\" style=\"padding-left:15px;\">
					<div class=\"titlen\">
						".(empty($rows['dir']) ? "Системное уведомление" : "")."
						<a style=\"font-size: 12px; margin-left: 13px;\" id=\"url\" onclick=\"delnot('{$rows['id']}');\">Удалить</a>
					</div>
					{$rows['content']}
				</td>
			</tr></table></div>";
	}else

	# Контент при отсутствии уведомлений
	$html .= '<h2 style="text-align:center;padding-top:70px;color:rgb(215, 215, 215);">Уведомлений нет</h2>';

	# Возврат контента
	return array(
		'content' 	=> $html, 
		'title' 	=> 'xCloud: Уведомления', 
		'app' 		=> false, 
		'redirect' 	=> false, 
		'error' 	=> '', 
		'css' 		=> '',
		'menu'		=> array('home' => false, 'notice' => true, 'search' => false)
	);
}