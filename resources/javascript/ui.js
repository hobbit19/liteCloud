// Входные переменные
var loading 	= '<div class="spin"></div>';
var url_data 	= location.href.split('?');
// События при нажатии на любой блок
window.onload = function()
{
	// События при нажатии на ссылку
	$(document).on('click', 'a', handlerAnchors);
	// События при переходе на предыдущую ссылку
	window.addEventListener("popstate", send_url);
	// Загрузка содержимого
	send_url();
	// 	Функция редактирование ссылки браузера
	function handlerAnchors()
	{
		// Приминение заголовка и ссылки
		var state = {title: this.getAttribute("title"), url: this.getAttribute("href", 2)}
		history.pushState(state, state.title, state.url);
		// Применяем изменения шаблона
		send_url(); closemenu();
		return false;
	}
	// Функция получения контента с сервера
	function send_url()
	{
		// Убираем заглушку страницы
		$('[aria-hidden="true"]').attr('aria-hidden', 'false');
		// Отправляем POST запрос на сервер
		$.ajax({
			url:		'/?content', // Ссылка API контента
			type:		"POST", // Тип отправляемых данных
			data:		location.href.split('?')[1] + '&history=' + encodeURIComponent(url_data[1]), // Отправляемые данные
			// При удачном получении контента
			success: function(data)
			{
				// Декодируем JSON контент в массив
				var json = jQuery.parseJSON(data);
				// Присвоение название программы
				$("title").html("liteCloud " + json.title);
				$("#logo h2").html(json.title + "<div id=\"open\"></div>");
				$('#applications').html(json.menu);
				// Если возникла оишбка в ходе проверки программы
				if(json.status != 1)
				{
					$("body .load_page").html(json.html);
					$("#logo h2").html("Ошибка<div id=\"open\"></div>");
					$("body .load_page").attr('aria-hidden', 'true');
					$('body .content').attr('aria-blur', 'true');
				}else
				// Если ошибки при проверки невыявлено
				$(".content .data").html(json.html);
			}
		});
		return false;
	}
	// Событие открытия меню системы
	$(document).on('click', '#logo h2', function()
	{
		// Если меню закрыто
		if($("h2 #open").attr('class') != 'rotation') getmenu();
		else closemenu(); // Закрытие меню
	});
	// Убираем панель загрузки страницы
	$('body .load_page').attr('aria-hidden',  'false');
	$('body .content').attr('aria-blur', 'false');
}
// Функция закрытия панели уведомления
function load_close()
{
	$('.load_page').attr('aria-hidden',  'false'); // Делаем блок видемым
	$('body .content').attr('aria-blur', 'false'); // Убираем эфекты
	$('body .load_page').html("<div class=\"spin\"></div>"); // Вставляем контент
}
// Функция открытия меню
function getmenu()
{
	$(".menu").css({"top" : "60px"}); // Перемещаем линию меню
	$(".menu #applications").css({"top" : "0px"}); // Показываем блок меню
	$("h2 #open").attr('class', 'rotation'); // Поворачиваем стрелку меню
}
// Функция закрытия меню
function closemenu()
{
	$(".menu").css({"top" : "0px"}); // Перемещаем линию меню
	$(".menu #applications").css({"top" : "-60px"}); // Убираем блок меню
	$("h2 #open").attr('class', '');  // Убираем поворот стрелки меню
}
