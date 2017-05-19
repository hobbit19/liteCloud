<?php
		$alias_info = Project::app_alias('fileinfo');

		$data = Project::scan_dir($dir);
		$dir_ex = explode('/', $_POST['dir']);
		$bh = ''; 

		$menu = '
		<a href="'.$APP['url_app'].'&dir='.urlencode($_POST['dir']).'&create=1&history_url='.urlencode($APP['history']).'">
			<div id="add_fl_i"></div>
			<div id="add_fl_t"> Создать</div>
		</a>
		<a href="'.$APP['url_app'].'&dir='.urlencode($_POST['dir']).'&upload=1&history_url='.urlencode($APP['history']).'">
			<div id="add_fl_u"></div>
			<div id="add_fl_t"> Загрузить</div>
		</a>';

		if($dir[strlen($dir)-1] != '/')
		{
			$APP['tmp']->set_cycle('content', 'kjk');
		}else
		{
			for($i=0;$i<count($dir_ex)-1;$i++)
				if($i != 0 && !empty($dir_ex[$i]))
				{
					$der_way = explode($dir_ex[$i], $_POST['dir']);
					$bh .= '<a href="'.$APP['url_app'].'&dir='.$der_way[0].$dir_ex[$i].'/" id="right">'.$dir_ex[$i].'</a>';
				}

			if($_POST['dir'] == '/') $menu .= '<a href="/?path=status" target="_blank"><div id="add_fl_ii"></div><div id="add_fl_t"> Информация</div></a>';

			$APP['tmp']->set('menu_mv', $menu);
			$APP['tmp']->set('pad', '
			<div id="devices">
				<div class="block">
					<a style="padding-left:0px;" href="'.$APP['url_app'].'&dir=/" id="right">Дом</a>'.$bh.'
				</div>
			</div>');

			if(count($data['dirs']) > 0)
				for($j=0;$j<count($data['dirs']);$j++)
					if(!empty($data['dirs'][$j]['name']))
						$APP['tmp']->set_cycle('content', '
						<div id="block_ff">
						<a href="/?path=home&app='.$alias_info['id'].'&file='.urlencode($_POST['dir'].$data['dirs'][$j]['name']).'&history_url='.urlencode($APP['history']).'">
						<img id="blockinf" src="'.$APP['dir'].'/icons/thumb.png"></a><a href="'.$APP['url_app'].'&dir='.urlencode($_POST['dir'].$data['dirs'][$j]['name']).'/">
						<div class="cont_n"><img src="'.$APP['dir'].'/icons/dir.png"></div><div class="title_n">'.$data['dirs'][$j]['name'].'</div></a></div>');
			if(count($data['files']) > 0)
				for($j=0;$j<count($data['files']);$j++)
					if(!empty($data['files'][$j]['name']))
					{
						$ex_name = explode('.', $data['files'][$j]['name']); $dot = '';
						$file_type = (empty($data['files'][$j]['type'])) ? 'Anknow' : $data['files'][$j]['type'];	
						if(count($ex_name) >= 2) $dot = $ex_name[count($ex_name)-1];
						$name = (strlen($data['files'][$j]['name']) > 19) ?  substr($data['files'][$j]['name'], 0, 17).'...'.mb_substr($dot, 0, 5, 'UTF-8') : $data['files'][$j]['name'];
						if(isset($APP['association'][$file_type]))
							$url = 'href="/?path=home&app='.$APP['association'][$file_type].'&file_path='.urlencode($_POST['dir'].$data['files'][$j]['name']).'&history_url='.urlencode($APP['history']).'"';
						else $url = 'href="/?path=get&file='.urlencode($_POST['dir'].$data['files'][$j]['name']).'&download" target="_self"';
						$file_ico = (file_exists(ROOT_PATH.$APP['dir'].'/icons/'.$file_type.'.png')) ? $APP['dir'].'/icons/'.$file_type.'.png' : $APP['dir'].'/icons/Anknow.png';
						$APP['tmp']->set_cycle('content', '
						<div id="block_ff">
						<a href="/?path=home&app='.$alias_info['id'].'&file='.urlencode($_POST['dir'].$data['files'][$j]['name']).'&history_url='.urlencode($APP['history']).'"><img id="blockinf" src="'.$APP['dir'].'/icons/thumb.png"></a>
						<a '.$url.'><div class="cont_n"><img src="'.$file_ico.'">
						</div><div class="title_n">'.$name.'</div></a></div>');
					}
			if(count($data['files']) < 1 && count($data['dirs']) < 1)
			{
				$APP['tmp']->set_cycle('content', '<div style="padding:95px;text-align:center;"><h2 style="color:rgba(0, 0, 0, 0.13);">Директория пуста</h2></div>');
				$APP['tmp']->set('disp_type', 'none');
			}
		}