<?php

if (!defined('NGCMS')) die ('HAL');

pluginsLoadConfig();
LoadPluginLang('avatar', 'config', '', '', '#');

switch ($_REQUEST['action']) {
	case 'about':			about();		break;
	case 'add_cat':			add_cat();		break;
	case 'edit_cat':		edit_cat();		break;
	case 'upload':			upload();		break;
	case 'list_cat':		list_cat();		break;
	case 'manage':			manage();		break;
	case 'remove':			remove();		break;
	default: main();
}

function unzip($from, $to, $folder)
{

copy ($from, $to);

if (is_file($to)) {

include_once(dirname(__FILE__) . '/lib/pclzip.lib.php');

$zip = new PclZip($to);
if (($list = $zip->listContent()) == 0) { 

	msg(array('type' => 'info', 'text' => "Ошибка!", 'info' => "Загрузка файлов не удалась!"));
	return print_msg( 'error', $lang['avatar']['avatar'], 'Загрузка файлов не удалась!', 'javascript:history.go(-1)' ); 

}

$zip->extract($folder);

unlink ($to);

  }
}

function remove()
{
	global $mysql, $lang, $userROW;
	
	$cat_id = intval($_REQUEST['cat_id']);
	
	if( ! $cat_id ) {
		msg(array('type' => 'info', 'text' => "Ошибка!", 'info' => "Не возможно удалить категорию!"));
		return print_msg( 'error', $lang['avatar']['avatar'], 'Не возможно удалить категорию!', 'javascript:history.go(-1)' );
	}
	
	$old_cats = file( extras_dir . '/avatar/lib/avatars.cats.php' );
	$new_cats = fopen( extras_dir . '/avatar/lib/avatars.cats.php', "w" );
	
	foreach ( $old_cats as $old_cats_list ) {
		$cat_arr = explode( "|", $old_cats_list );
		if( $cat_arr[0] != $cat_id ) {
			fwrite( $new_cats, $old_cats_list );
		}
	}
	fclose( $new_cats );
	
	msg(array('type' => 'info', 'info' => "Категория успешно удалена"));
	print_msg( 'delete', $lang['avatar']['avatar'], 'Категория успешно удалена!', '?mod=extra-config&plugin=avatar&action=list_cat' );
	return;
}

function about()
{global $twig, $lang, $breadcrumb;
	$tpath = locatePluginTemplates(array('main', 'about'), 'avatar', 1);
	$breadcrumb = breadcrumb('<i class="fa fa-universal-access btn-position"></i><span class="text-semibold">'.$lang['avatar']['avatar'].'</span>', array('?mod=extras' => '<i class="fa fa-puzzle-piece btn-position"></i>'.$lang['extras'].'', '?mod=extra-config&plugin=avatar' => '<i class="fa fa-universal-access btn-position"></i>'.$lang['avatar']['avatar'].'',  '<i class="fa fa-exclamation-circle btn-position"></i>'.$lang['avatar']['about'].'' ) );

	$xt = $twig->loadTemplate($tpath['about'].'about.tpl');
	$tVars = array();
	$xg = $twig->loadTemplate($tpath['main'].'main.tpl');
	
	$about = 'версия 0.1';
	
	$tVars = array(
		'global' => 'О плагине',
		'header' => $about,
		'entries' => $xt->render($tVars)
	);
	
	print $xg->render($tVars);
}

function upload()
{global $twig, $mysql, $parse, $lang, $userROW, $breadcrumb;

	$tpath = locatePluginTemplates(array('main', 'upload', ''), 'avatar', 1);
	$breadcrumb = breadcrumb('<i class="fa fa-universal-access btn-position"></i><span class="text-semibold">'.$lang['avatar']['avatar'].'</span>', array('?mod=extras' => '<i class="fa fa-puzzle-piece btn-position"></i>'.$lang['extras'].'', '?mod=extra-config&plugin=avatar' => '<i class="fa fa-universal-access btn-position"></i>'.$lang['avatar']['avatar'].'',  ''.$lang['avatar']['upload_img'].'' ) );

	if (isset($_REQUEST['submit'])) {

	$target = avatars_dir ."/list/".$_REQUEST['category'];
	$category = $_REQUEST['category'];

	$allowed_extensions = array ("gif", "jpg", "png", "jpe", "jpeg");

	if ($_FILES['uphard']['tmp_name']) {

		$exp = explode (".", $_FILES['uphard']['name']);
		$format = end ($exp);
		
		if (in_array ($format, $allowed_extensions)) {

			move_uploaded_file($_FILES['uphard']['tmp_name'], $target .'/' . $_FILES['uphard']['name']);

		} else {
		
			msg(array('type' => 'error', 'info' => "Некорректные типы файлов!"));
			return print_msg( 'error', $lang['avatar']['avatar'], 'К загрузке допустимы только аватары следующих форматов: <b>gif, jpg, jpeg, jpe, png</b>', '?mod=extra-config&plugin=avatar' );
			
		}

	}

	if ($_FILES['zip_archive']['tmp_name']) {
		unzip ($_FILES['zip_archive']['tmp_name'], $target."/".$_FILES['zip_archive']['name'], $target);
	}

	if ($_REQUEST['upurl']) {
		$filename = basename ($_REQUEST['upurl']);
		copy ($_REQUEST['upurl'], $target."/".$filename);
	}

	if ($_REQUEST['upzipurl']) {
		$filename = basename ($_REQUEST['upzipurl']);
		unzip ($_REQUEST['upzipurl'], $target."/".$filename, $target);
	}
	
	$date = time() + ($config['date_adjust'] * 60);

	if ($dir = opendir($target)) {
		while ($file = readdir($dir)) {
			if ($file != "." && $file != ".."){
				$mysql->query('INSERT INTO '.prefix.'_avatars (av_name, author, category, time) VALUES ('.db_squote($file).', '.db_squote($userROW['id']).', '.db_squote($category).', '.db_squote($date).')');
			}
		}
		closedir($dir);
	}
		
		msg(array('type' => 'info', 'info' => "Загрузка файлов прошла успешно!"));
		return print_msg( 'success', $lang['avatar']['avatar'], 'Аватары успешно загружены в папку <strong>'.$category.'</strong>!', '?mod=extra-config&plugin=avatar&action=upload' );
	
	}
	
	$category_sel1 = '<select name="category">';
	$category_sel1 .= '<option value="">--</option>';

	$list = file( extras_dir . '/avatar/lib/avatars.cats.php' );
	
	for ($k=0; $k<count($list); $k++){
		$a = explode("|", $list[$k]);

		if ($_GET['category'] == $a[2]) $selected = " selected"; else $selected = "";

		if ($a[3] == "1") {
			$category_sel1 .= '<option value="'.$a[2].'"'.$selected.'>'.$a[1].'</option>';
		}

	}
	$category_sel1 .= '</select>';

	$xt = $twig->loadTemplate($tpath['upload'].'upload.tpl');
	
	$tVars = array(
		'category_sel1' 		=> $category_sel1,
	);
	
	$xg = $twig->loadTemplate($tpath['main'].'main.tpl');
	
	$tVars = array(
		'global' => $lang['avatar']['upload_img'],
		'header' => '<i class="fa fa-exclamation-circle"></i> <a href="?mod=extra-config&plugin=avatar&action=about">'.$lang['avatar']['about'].'</a>',
		'active3' => 'active',
		'entries' => $xt->render($tVars)
	);
	
	print $xg->render($tVars);
}

function add_cat()
{global $twig, $mysql, $parse, $lang, $userROW, $breadcrumb;

	$tpath = locatePluginTemplates(array('main', 'add_cat', ''), 'avatar', 1);
	$breadcrumb = breadcrumb('<i class="fa fa-universal-access btn-position"></i><span class="text-semibold">'.$lang['avatar']['avatar'].'</span>', array('?mod=extras' => '<i class="fa fa-puzzle-piece btn-position"></i>'.$lang['extras'].'', '?mod=extra-config&plugin=avatar' => '<i class="fa fa-universal-access btn-position"></i>'.$lang['avatar']['avatar'].'',  ''.$lang['avatar']['add_cat'].'' ) );

	if (isset($_REQUEST['submit'])) {
		$name = secure_html(trim($_REQUEST['name']));
		$upload = $_REQUEST['upload'];
		$hidden = $_REQUEST['hidden'];

		$altname = secure_html(trim($_REQUEST['altname']));
		if (trim ( $altname ) == '' ){
			$altname = $parse->translit($name);
		} else {
			$altname = $parse->translit($altname);
		}
		
		if ( $name == "" ){
			msg(array("type" => "error", "text" => 'Вы не ввели имя категории'));
		}
	
		$cat_id = intval( $_REQUEST['cat_id'] );
		$cat_id = time();
	
		$all_items = file( extras_dir . '/avatar/lib/avatars.cats.php' );
		foreach ( $all_items as $item_line ) {
			$item_arr = explode( "|", $item_line );
			if( $item_arr[0] == $cat_id ) {
				$cat_id ++;
			}
		}
	
		foreach ( $all_items as $cat_list ) {
			$cat_arr = explode( "|", $cat_list );
			if( $cat_arr[1] == $name ) {
				print_msg( "error", "Ошибка", "Данная категория уже существует", "?mod=extra-config&plugin=avatar" );
			}
		}
	
		$new_cats = fopen( extras_dir . '/avatar/lib/avatars.cats.php', "a" );
		$name = str_replace( "|", "&#124", $name );
		$altname = str_replace( "|", "&#124", $altname );
		fwrite( $new_cats, "$cat_id|$name|$altname|$upload|$hidden|\n" );
		fclose( $new_cats );

		@mkdir(dirname(dirname(dirname(dirname(__FILE__)))). "/uploads/avatars/list/".$altname, 0777);

		msg(array('type' => 'info', 'info' => "Категория успешно добавлена"));
		print_msg( 'success', $lang['avatar']['avatar'], 'Категория успешно добавлена!', '?mod=extra-config&plugin=avatar' );
		return true;
	}
	
	$xt = $twig->loadTemplate($tpath['add_cat'].'add_cat.tpl');
	
	$tVars = array(
		'name' 		=> $name,
		'altname' 	=> $altname,
		'upload' 	=> $upload,
		'hidden' 	=> $hidden,

	);
	
	$xg = $twig->loadTemplate($tpath['main'].'main.tpl');
	
	$tVars = array(
		'global' => $lang['avatar']['add_cat'],
		'header' => '<i class="fa fa-exclamation-circle"></i> <a href="?mod=extra-config&plugin=avatar&action=about">'.$lang['avatar']['about'].'</a>',
		'active2' => 'active',
		'entries' => $xt->render($tVars)
	);
	
	print $xg->render($tVars);
}

function edit_cat()
{global $twig, $mysql, $parse, $lang, $userROW, $breadcrumb;

	$tpath = locatePluginTemplates(array('main', 'edit_cat', ''), 'avatar', 1);
	$breadcrumb = breadcrumb('<i class="fa fa-universal-access btn-position"></i><span class="text-semibold">'.$lang['avatar']['avatar'].'</span>', array('?mod=extras' => '<i class="fa fa-puzzle-piece btn-position"></i>'.$lang['extras'].'', '?mod=extra-config&plugin=avatar' => '<i class="fa fa-universal-access btn-position"></i>'.$lang['avatar']['avatar'].'',  ''.$lang['avatar']['edit_cat'].'' ) );

	$cat_id = intval($_REQUEST['cat_id']);
	
	if (isset($_REQUEST['submit'])) {
	
		$name = secure_html(trim($_REQUEST['name']));
		$upload = $_REQUEST['upload'];
		$hidden = $_REQUEST['hidden'];
		$altname = secure_html(trim($_REQUEST['altname']));
		if (trim ( $altname ) == '' ){
			$altname = $parse->translit($name);
		} else {
			$altname = $parse->translit($altname);
		}
		$name = str_replace( "|", "&#124", $name );
		$altname = str_replace( "|", "&#124", $altname );
	
		if( $name == "" ) {
			msg(array('type' => 'error', 'info' => "Ошибка"));
			return print_msg( 'error', $lang['avatar']['avatar'], 'Ошибка!', 'javascript:history.go(-1)' );
		}
	
		$old_cats = file( extras_dir . '/avatar/lib/avatars.cats.php' );
		$new_cats = fopen( extras_dir . '/avatar/lib/avatars.cats.php', "w" );
	
		foreach ( $old_cats as $cat_list ) {
			$cat_arr = explode( "|", $cat_list );
			if( $cat_arr[0] == $cat_id ) {
				fwrite( $new_cats, "$cat_id|$name|$altname|$upload|$hidden|\n" );
			} else {
				fwrite( $new_cats, $cat_list );
			}
		}

		fclose( $new_cats );
	
		msg(array('type' => 'info', 'info' => "Категория успешно добавлена"));
		print_msg( 'success', $lang['avatar']['avatar'], 'Категория успешно добавлена!', '?mod=extra-config&plugin=avatar' );
		return true;
	}
	
	$xt = $twig->loadTemplate($tpath['edit_cat'].'edit_cat.tpl');
	
	$all_words = file( extras_dir . '/avatar/lib/avatars.cats.php' );
	
	foreach ( $all_words as $cat_list ) {
		$cat_arr = explode( "|", $cat_list );
		if( $cat_arr[0] == $cat_id ) {

			if ($cat_arr[3] == "1") {
				$ch_upload_0 = "";
				$ch_upload_1 = "checked";
			} else {
				$ch_upload_0 = "checked";
				$ch_upload_1 = "";
			}

			if ($cat_arr[4] == "1") {
				$ch_hidden_0 = "";
				$ch_hidden_1 = " checked";
			} else {
				$ch_hidden_0 = " checked";
				$ch_hidden_1 = "";
			}

			$tVars = array(
				'name' 			=> $cat_arr[1],
				'altname' 		=> $cat_arr[2],
				'ch_upload_1' 	=> $ch_upload_1,
				'ch_upload_0' 	=> $ch_upload_0,
				'ch_hidden_1' 	=> $ch_hidden_1,
				'ch_hidden_0' 	=> $ch_hidden_0,
				'cat_arr' 		=> $cat_arr[0],
			);
			
			$cat_name = $cat_arr[1];
		}
	}
	
	$xg = $twig->loadTemplate($tpath['main'].'main.tpl');
	
	$tVars = array(
		'global' => ''.$lang['avatar']['edit_cat'].' '.$cat_name.'',
		'header' => '<i class="fa fa-exclamation-circle"></i> <a href="?mod=extra-config&plugin=avatar&action=about">'.$lang['avatar']['about'].'</a>',
		'entries' => $xt->render($tVars)
	);
	
	print $xg->render($tVars);
}
	
function list_cat()
{global $twig, $mysql, $parse, $lang, $userROW, $breadcrumb;
	$tpath = locatePluginTemplates(array('main', 'list_avatar', 'list_avatar_entries'), 'avatar', 1);
	$breadcrumb = breadcrumb('<i class="fa fa-universal-access btn-position"></i><span class="text-semibold">'.$lang['avatar']['avatar'].'</span>', array('?mod=extras' => '<i class="fa fa-puzzle-piece btn-position"></i>'.$lang['extras'].'', '?mod=extra-config&plugin=avatar' => '<i class="fa fa-universal-access btn-position"></i>'.$lang['avatar']['avatar'].'',  ''.$lang['avatar']['list_cat'].'' ) );

	$all_words = file( extras_dir . '/avatar/lib/avatars.cats.php' );
	$count_words = 0;

	usort( $all_words, "compare_filter" );
	
	$xe = $twig->loadTemplate($tpath['list_avatar_entries'].'list_avatar_entries.tpl');
	
	foreach ( $all_words as $cat_list ) {
		$cat_arr = explode( "|", $cat_list );
		$num ++;
		$av_num = 0;
		if ($dir = opendir(avatars_dir ."/list/".$cat_arr[2])) {
			while ($file = readdir($dir)) {
				if ($file != "." && $file != ".."){
					$av_num ++;
				}
			}
			closedir($dir);
		}


		if ($cat_arr[3] == '1') $upload = "<font color='green'>да</font>"; else $upload = "<font color='red'>нет</font>";
		if ($cat_arr[4] == '1') $hidden = "<font color='red'>да</font>"; else $hidden = "<font color='green'>нет</font>";

		$count_words ++;
	
		$tVars = array (
			'id' => $num,
			'name' => '<a href="?mod=avatars&action=manage&category='.$cat_arr[2].'">'.$cat_arr[1].'</a>',
			'altname' => $cat_arr[2],
			'upload' => $upload,
			'hidden' => $hidden,
			'av_num' => $av_num,
			'del' => '<a href="?mod=extra-config&plugin=avatar&action=edit_cat&cat_id='.$cat_arr[0].'">[Редактировать]</a> <a href="?mod=extra-config&plugin=avatar&action=remove&cat_id='.$cat_arr[0].'">[Удалить]</a>',

		);
		
		if( $count_words == 0 ) {
			
		} else {
			$entries .= $xe->render($tVars);
		}
	}
	
	$xt = $twig->loadTemplate($tpath['list_avatar'].'list_avatar.tpl');
	
	$tVars = array(
		'pagesss' => generateAdminPagelist( array(	'current' => $pageNo,
													'count' => $countPages,
													'url' => '?mod=extra-config&plugin=avatar&action=list_avatar&page=%page%'
													)
		),
		'entries' => $entries 
	);
	
	$xg = $twig->loadTemplate($tpath['main'].'main.tpl');
	
	$tVars = array(
		'global' => $lang['avatar']['list_cat'],
		'header' => '<i class="fa fa-exclamation-circle"></i> <a href="?mod=extra-config&plugin=avatar&action=about">'.$lang['avatar']['about'].'</a>',
		'active4' => 'active',
		'entries' => $xt->render($tVars)
	);
	
	print $xg->render($tVars);
}

function manage()
{global $twig, $mysql, $parse, $lang, $userROW, $breadcrumb, $img_id;
	$tpath = locatePluginTemplates(array('main', 'manage_avatar', 'manage_avatar_entries'), 'avatar', 1);
	$breadcrumb = breadcrumb('<i class="fa fa-universal-access btn-position"></i><span class="text-semibold">'.$lang['avatar']['avatar'].'</span>', array('?mod=extras' => '<i class="fa fa-puzzle-piece btn-position"></i>'.$lang['extras'].'', '?mod=extra-config&plugin=avatar' => '<i class="fa fa-universal-access btn-position"></i>'.$lang['avatar']['avatar'].'',  ''.$lang['avatar']['manag_img'].'' ) );

	$cat_link = "?mod=extra-config&plugin=avatar&action=manage&category=";

	$category_sel = '<select onchange="self.location.href = this.options[this.selectedIndex].value" name="category">';
	$category_sel .= '<option value="">--</option>';

	$list = file( extras_dir . '/avatar/lib/avatars.cats.php' );
	for ($k=0; $k<count($list); $k++){
		$a = explode("|", $list[$k]);

		if ($_GET['category'] == $a[2]) $selected = " selected"; else $selected = "";

		$category_sel .= '<option value="'.$cat_link.$a[2].'"'.$selected.'>'.$a[1].'</option>';

	}

	$category_sel .= '</select>';

	$category = avatars_dir."/list/".$_GET['category'];

	$av_num = 0;

	if ($dir = opendir($category)) {
		while ($file = readdir($dir)) {
			if ($file != "." && $file != ".."){
				$av_num ++;
			}
		}
		closedir($dir);
	}

	if ($_GET['category']) {
		$avat_num = 'Аватаров в категории: '.$av_num;
	} else {
		$avat_num = 'ничего не выбрано';
	}

	$upload_link = "<td align=\"right\">[<a href=\"?mod=extra-config&plugin=avatar&action=upload&category=".$_GET['category']."\">Загрузить аватары</a>]</td>";

	$xe = $twig->loadTemplate($tpath['manage_avatar_entries'].'manage_avatar_entries.tpl');

	if ($_GET['category']) {
		
		$category = avatars_dir."/list/".$_GET['category'];
	
		if ($handle = opendir($category)) {
			while (false !== ($file = readdir($handle))){

			$i++; $s++;

			$this_size = @filesize( $category."/".$file );
			$img_info = @getimagesize( $category."/".$file );
            $total_size += @filesize( $category."/".$file );

			if ($file != "." && $file != ".."){
				
				if (isset($_REQUEST['submit'])) {
					
					if( ! isset( $_REQUEST['images'] ) ) {
						msg(array('type' => 'info', 'text' => "Невозможно удалить картинку!", 'info' => "Выберите картинки, которые нужно удалить!"));
						return print_msg( 'error', $lang['avatar']['avatar'], 'Выберите картинки '.$_REQUEST['images'].', которые нужно удалить!', 'javascript:history.go(-1)' );
					}
					
					$row = $mysql->record("select * from " . prefix . "_avatars where av_name = " . db_squote($_REQUEST['images'])." LIMIT 1");
					$mysql->query('delete from '.prefix.'_avatars where id = '.$row['id'].'');

					unlink( $category."/".$_REQUEST['images'] );

					msg(array('type' => 'info', 'text' => "Картинки(а) успешно удалены с сервера!"));
					return print_msg( 'delete', $lang['avatar']['avatar'], 'Выбранные картинки(а) успешно удалены с сервера!', '?mod=extra-config&plugin=avatar&action=manage' );
				}

				$dir = opendir(avatars_dir ."/list/".$cat_arr[2]);
				$img_id = $file;
				$name = $file;
				$image = '<a target="_blank" href="/uploads/avatars/list/'.$_GET['category'].'/'.$file.'"><img src="/uploads/avatars/list/'.$_GET['category'].'/'.$file.'"></a>';
				$size = formatsize( $this_size );
				$img_i = ''.$img_info[0].'x'.$img_info[1].'';
				$del = '<input type="checkbox" name="images" value="'.$file.'">';
			}
			
			$tVars = array (
				'id' => $num,
				'name' => $name,
				'image' => $image,
				'size' => $size,
				'img_i' => $img_i,
				'del' => $del,
			);
		
			$entri .= $xe->render($tVars);
			}
		closedir($handle);
		}
		
		
		if ($av_num == "0" ) {
			$entries = 'нет';
		} else {
			$entries = $entri;
		}
	}
	
	$xt = $twig->loadTemplate($tpath['manage_avatar'].'manage_avatar.tpl');
	
	$tVars = array(
		'pagesss' => generateAdminPagelist( array(	'current' => $pageNo,
													'count' => $countPages,
													'url' => '?mod=extra-config&plugin=avatar&action=list_avatar&page=%page%'
													)
		),
		'category_sel' => $category_sel,
		'avat_num' => $avat_num,
		'upload_link' => $upload_link,
		'total_size' => formatsize( $total_size ),
		'entries' => $entries
	);
	
	$xg = $twig->loadTemplate($tpath['main'].'main.tpl');
	
	$tVars = array(
		'global' => $lang['avatar']['manag_img'],
		'header' => '<i class="fa fa-exclamation-circle"></i> <a href="?mod=extra-config&plugin=avatar&action=about">'.$lang['avatar']['about'].'</a>',
		'active5' => 'active',
		'entries' => $xt->render($tVars)
	);
	
	print $xg->render($tVars);
}

function main()
{global $twig, $lang, $breadcrumb;
	
	$tpath = locatePluginTemplates(array('main', 'general.from'), 'avatar', 1);
	$breadcrumb = breadcrumb('<i class="fa fa-universal-access btn-position"></i><span class="text-semibold">'.$lang['avatar']['avatar'].'</span>', array('?mod=extras' => '<i class="fa fa-puzzle-piece btn-position"></i>'.$lang['extras'].'', '?mod=extra-config&plugin=avatar' => '<i class="fa fa-universal-access btn-position"></i>'.$lang['avatar']['avatar'].'' ) );

	if (isset($_REQUEST['submit'])){
		pluginSetVariable('avatar', 'av_per_page', intval($_REQUEST['av_per_page']));
		pluginSetVariable('avatar', 'av_serv_del', intval($_REQUEST['av_serv_del']));

		pluginSetVariable('visited', 'localsource', (int)$_REQUEST['localsource']);
		pluginsSaveConfig();
		msg(array("type" => "info", "info" => "сохранение прошло успешно"));
		return print_msg( 'info', ''.$lang['avatar']['avatar'].'', 'Cохранение прошло успешно', 'javascript:history.go(-1)' );
	}
	
	$av_per_page = pluginGetVariable('avatar', 'av_per_page');

	$av_serv_del = pluginGetVariable('avatar', 'av_serv_del');
	$av_serv_del = '<option value="0" '.($av_serv_del==0?'selected':'').'>нет</option><option value="1" '.($av_serv_del==1?'selected':'').'>да</option>';
	
	$xt = $twig->loadTemplate($tpath['general.from'].'general.from.tpl');
	$xg = $twig->loadTemplate($tpath['main'].'main.tpl');
	
	$tVars = array(
		'av_per_page' => $av_per_page,
		'av_serv_del' => $av_serv_del,
		'localsource'       => MakeDropDown(array(0 => 'Шаблон сайта', 1 => 'Плагина'), 'localsource', (int)pluginGetVariable('visited', 'localsource')),
	);
	
	$tVars = array(
		'global' => 'Общие',
		'header' => '<i class="fa fa-exclamation-circle"></i> <a href="?mod=extra-config&plugin=avatar&action=about">'.$lang['avatar']['about'].'</a>',
		'active1' => 'active',
		'entries' => $xt->render($tVars)
	);
	
	print $xg->render($tVars);
}

?>