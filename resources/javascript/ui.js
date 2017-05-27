var loading 	= '<div class="spin"></div>';
var url_data 	= location.href.split('?');

window.onload = function()
{
	$(document).on('click', 'a', handlerAnchors);
	window.addEventListener("popstate", send_url);
	send_url();

	function handlerAnchors()
	{
		var state = {title: this.getAttribute("title"), url: this.getAttribute("href", 2)}
		history.pushState(state, state.title, state.url);
		send_url();
		return false;
	}

	function send_url()
	{
		$('[aria-hidden="true"]').attr('aria-hidden', 'false');
		$.ajax({
			url:		'/?content',
			type:		"POST",
			data:		url_data[1] + '&history=' + encodeURIComponent(url_data[1]),
			success: function(data)
			{
				var json = jQuery.parseJSON(data);
				// Присвоение название программы
				$("title").html("liteCloud " + json.title);
				$("#logo h2").html(json.title + "<div id=\"open\"></div>");
				// Если возникла оишбка в ходе проверки программы
				if(json.status != 1)
				{
					$("body .load_page").html(json.html);
					$("body .load_page").attr('aria-hidden', 'true');
					$('body .content').attr('aria-blur', 'true');
				// Если ошибки при проверки невыявлено
				}else
					$(".content .data").html(json.html);
			}
		});
		return false;
	}

	$(document).on('click', '#logo h2', function()
	{
		if($('.onload .app_menu').attr('aria-hidden') == 'false')
		{
			$('.onload .app_menu').attr('aria-hidden', 'true');
			$("h2 #open").attr('class', 'rotation');
			$('body .content .data').attr('aria-blur', 'true');
		}else
		{
			$('[aria-hidden="true"]').attr('aria-hidden', 'false');
			$("h2 #open").attr('class', '');
			$('body .content .data').attr('aria-blur', 'false');
		}
	});

	$(document).on('click', '#info #bell_panel', function()
	{
		if($('.onload .task').attr('aria-hidden') == 'false')
		{
			$('.onload .task').attr('aria-hidden', 'true');
			$('body .content .data').attr('aria-blur', 'true');
		}else
		{
			$('[aria-hidden="true"]').attr('aria-hidden', 'false');
			$('body .content .data').attr('aria-blur', 'false');
		}
	});

	$(document).on('click', '#info #settings', function()
	{
		if($('.onload .settings_app').attr('aria-hidden') == 'false')
		{
			$('.onload .settings_app').attr('aria-hidden', 'true');
			$('body .content .data').attr('aria-blur', 'true');
		}else
		{
			$('[aria-hidden="true"]').attr('aria-hidden', 'false');
			$('body .content .data').attr('aria-blur', 'false');
		}
	});

	$('body .load_page').attr('aria-hidden',  'false');
	$('body .content').attr('aria-blur', 'false');
}

function load_close()
{
	$('.load_page').attr('aria-hidden',  'false');
	$('body .content').attr('aria-blur', 'false');
	$('body .load_page').html("<div class=\"spin\"></div>");
}
