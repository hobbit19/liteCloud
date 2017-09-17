//if(effectblock != "" && $("*").is(effectblock)) { $(effectblock).addClass("toleft").delay(100).fadeIn(); maincontent(); }
// Входные переменные
var loading 	= '<div class="spin"></div>';
var url_data 	= location.href.split('?');
// События при нажатии на любой блок
window.onload = function()
{
	// 	Функция редактирование ссылки браузера
	function handlerAnchors()
	{
		// Переадресация при атребуте _self
		if(this.getAttribute("target") == "_self") window.location.href = this.getAttribute("href", 2);
		// Приминение заголовка и ссылки
		var state = {title: this.getAttribute("title"), url: this.getAttribute("href", 2)}
		history.pushState(state, state.title, state.url);
		// Применяем изменения шаблона
		send_url(($(this).is('[left-effect]')) ? $(this).attr('left-effect') : ""); 
		// Закрываем меню и возвращаем ответ
		closemenu(); return false;
	}
	// Функция получения контента с сервера
	function send_url(effectblock)
	{
		// Убираем заглушку страницы
		$('[aria-hidden="true"]').attr('aria-hidden', 'false');
		$('[aria-blur="true"]').attr('aria-blur', 'false');
		// Условие показа блока с эфектом перелистывания
		if(effectblock != "" && $("*").is(effectblock)) $(effectblock).animate({'left':'-200px', 'opacity':0}, 150, maincontent);
		// Если эфекта нету
		else maincontent(); return false;
	}
	// Событие открытия меню системы
	$(document).on('click', '#logo h2', function()
	{
		// Если меню закрыто
		if($("h2 #open").attr('class') != 'rotation') getmenu();
		else closemenu(); // Закрытие меню
	});
	// Событие для отправки внутренних запросов приложения
	$(document).on('click', 'appquery', function()
	{
		// Массив входящих запросов
		var values = {};
		// Циклическая запись всех input тегов
		$('.content input').each(function()
		{
		    values[this.name] = this.value; // ассоциативный массив
		}); // Отправление запроса на выход
		$.ajax({
			url: '/?content', // Ссылка API контента
			type: "POST", // Тип отправляемых данных
			data: location.href.split('?')[1] + '&history=' + encodeURIComponent(url_data[1])
				+ '&appquery=' + encodeURIComponent(JSON.stringify(values)), // Отправляемые данные
			// При удачном получении контента
			success: function(data) { setcontent(data); }
		});
	});
	// Событие для отправки внутренних запросов уведомлений
	$(document).on('click', 'winquery', function()
	{
		// Массив входящих запросов
		var values = {};
		// Циклическая запись всех input тегов
		$('.load_page input').each(function()
		{
		    values[this.name] = this.value; // ассоциативный массив
		}); // Циклическая запись всех select тегов
		$('.load_page select').each(function()
		{
		    values[this.name] = this.value; // ассоциативный массив
		}); // Отправление запроса на выход
		$.ajax({
			url: '/?content', // Ссылка API контента
			type: "POST", // Тип отправляемых данных
			data: location.href.split('?')[1] + '&history=' + encodeURIComponent(url_data[1])
				+ '&winquery=' + encodeURIComponent(JSON.stringify(values)), // Отправляемые данные
			// При удачном получении контента
			success: function(data) { setcontent(data); }
		}); // Если была использована пометка target
		if(this.getAttribute("target") == "_self") location.reload();
	}); // События при нажатии на ссылку или на goto
	$(document).on('click', 'a',	handlerAnchors);
	$(document).on('click', 'goto',	handlerAnchors);
	// События при переходе на предыдущую ссылку
	window.addEventListener("popstate", send_url);
	// Загрузка содержимого
	send_url();
	// Убираем панель загрузки страницы
	$('body .load_page').attr('aria-hidden', 'false');
	$('body .content').attr('aria-blur', 'false');
}
// Фильтрация входящих данных
$(document).on('keyup', '[filter-input="true"]', function(event)
{
	event.preventDefault();
	// Записываем текущий контент поля
	var content = $(this).val();
	// Список запрещенных символов
	var symbols = ['.', '"', '/', '\\', '[', ']', ':', ';', '|', '=', ',', '?', '<', '>', '*', '\'', '&'];
	// Циклом удаляем символы
	for(i=0;i<17;i++)  for(j=0;j<content.length;j++)
	if(symbols[i] == content[j]) { alert(symbols[i] + " " + content[j]); content[j].replace(symbols[i], '!'); }
	// Применянем новое имя
	$(this).val(content);
});
// Функция получение контента страницы
function maincontent()
{
	// Отправляем POST запрос на сервер
	$.ajax({
		url: '/?content', // Ссылка API контента
		type: "POST", // Тип отправляемых данных
		data: location.href.split('?')[1] + '&history=' + encodeURIComponent(url_data[1]), // Отправляемые данные
		// При удачном получении контента
		success: function(data) { setcontent(data); }
	}); return false;
}
// Функция закрытия панели уведомления
function load_close()
{
	$(".withoutscroll").attr("class", "scrollbarcustom");
	$('.load_page').attr('aria-hidden',  'false'); // Делаем блок видемым
	$('body .content').attr('aria-blur', 'false'); // Убираем эфекты
	$('body .load_page').html("<div class=\"spin\"></div>"); // Вставляем контент
	$('.scrollbarcustom').scrollbar();
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
// Функция вывода контента
function setcontent(data)
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
		$("body .load_page").html(json.html); // Шаблон уведомлений
		$("#logo h2").html("Error<div id=\"open\"></div>"); // Вывод ошибки в меню
		$("body .load_page").attr('aria-hidden', 'true'); // Раскрытие блока
		$('body .content').attr('aria-blur', 'true'); // Раскрытие эффекта
	// Если ошибки при проверки невыявлено
	}else
	{
		// Присвоение контента
		$(".content .data").html("<div class=\"scrollbarcustom\">" + json.html + "</div>");
		// Вывод уведомления
		if(json.notice != '' && json.notice != null)
		{
			$(".scrollbarcustom").attr("class", "withoutscroll");
			$("body .load_page").html(json.notice); // Шаблон уведомлений
			$("body .load_page").attr('aria-hidden', 'true'); // Раскрытие блока
			$('body .content').attr('aria-blur', 'true'); // Раскрытие эффекта
		} // Генерация дополнительного меню
		if (json.topmenu != '' && json.topmenu != null) $("#info").html(json.topmenu);
		// Если значение пустое 
		else if (json.topmenu == null) $("#info").html("");
	}
	// Применяем новый скролл-бар
	$('.scrollbarcustom').scrollbar();
}
