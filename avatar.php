<?php

if (!defined('NGCMS')) die ('HAL');

register_plugin_page('avatar','','main_avatar');
register_plugin_page('avatar','cat','cat_avatar');
register_plugin_page('avatar','ava_id','set_avatar');

function main_avatar($params) {
	global $tpl, $template, $twig, $mysql, $SYSTEM_FLAGS, $config, $userROW, $lang;
	
    $tpath = locatePluginTemplates(array('skins/avatars.main'), 'avatar', pluginGetVariable('avatar', 'localsource'), pluginGetVariable('avatar','localskin'));
    $xt = $twig->loadTemplate($tpath['skins/avatars.main'].'skins/avatars.main.tpl');
	
	$SYSTEM_FLAGS['info']['title']['group'] = 'Аватары';
	
	if(!is_array($userROW))
       return msg(array("type" => "error", "info" => "Просматривать список аватаров могут, только зарегестрированные пользователи."));
	
	$cat_link = home."/avatar/cat/";
	$cat_z = '/';
	$http_avatars_dir = home.'/uploads/avatars/list';

	$category_sel = '<select onchange="self.location.href = this.options[this.selectedIndex].value" name="category">';
	$category_sel .= '<option style="color:#cccccc;" value="'.$avatars_link.'">- категория -</option>';

	$list = file( extras_dir . '/avatar/lib/avatars.cats.php' );
			
	for ($k=0; $k<count($list); $k++) {	
		$a = explode("|", $list[$k]);

		if ($_GET['category'] == $a[2]) $selected = " selected"; else $selected = "";

		if ($a[4] == "0") {
			$category_sel .= '<option value="'.$cat_link.$a[2].$cat_z.'"'.$selected.'>'.$a[1].'</option>';
		}

	}

	$category_sel .= '</select>';
			
	$new_avatar .= '<form action="ava_ok" method="post"><table width="100%" cellpadding="0">';
		foreach ($mysql->select('select * from '.prefix.'_avatars ORDER BY time ASC LIMIT 0,5') as $row){
			$new_avatar .= '
			<td align="center">
				<a href="/avatar/ava_id/'.$row['id'].'" title="'.$lang['avatars_set_alt'].'">
					<img src="'.$http_avatars_dir.'/'.$row['category'].'/'.$row['av_name'].'">
				</a>
			</td>';		
		}		
	$new_avatar .= '</tr></table></form>';
	
	$random_avatars .= '<form action="ava_ok" method="post"><table width="100%" cellpadding="0">';
		foreach ($mysql->select('select * from '.prefix.'_avatars ORDER BY rand() LIMIT 0,5') as $row){
			$random_avatars .= '
			<td align="center">
				<a href="/avatar/ava_id/'.$row['id'].'" title="'.$lang['avatars_set_alt'].'">
					<img src="'.$http_avatars_dir.'/'.$row['category'].'/'.$row['av_name'].'">
				</a>
			</td>';		
		}		
	$random_avatars .= '</tr></table></form>';
	
	$tVars = array(
		'category_sel'          => $category_sel,
		'new_avatars'          => $new_avatar ? $new_avatar : 'пока нет',
		'random_avatars'          => $random_avatars ? $random_avatars : 'пока нет',
	);

	$template['vars']['mainblock'] = $xt->render($tVars);

}

function cat_avatar($params) {
	global $tpl, $template, $twig, $mysql, $SYSTEM_FLAGS, $config, $userROW, $lang, $CurrentHandler;
	
    $tpath = locatePluginTemplates(array('skins/avatars.cat'), 'avatar', pluginGetVariable('avatar', 'localsource'), pluginGetVariable('avatar','localskin'));
    $xt = $twig->loadTemplate($tpath['skins/avatars.cat'].'skins/avatars.cat.tpl');
	
	$cat_link = home."/avatar/cat/";
	$cat_z = '/';
	$http_avatars_dir = home.'/uploads/avatars/list';

	$category_sel = '<select onchange="self.location.href = this.options[this.selectedIndex].value" name="category">';
	$category_sel .= '<option style="color:#cccccc;" value="'.$avatars_link.'">- категория -</option>';

	$list = file( extras_dir . '/avatar/lib/avatars.cats.php' );
			
	for ($k=0; $k<count($list); $k++) {	
		$a = explode("|", $list[$k]);

		if ($_GET['category'] == $a[2]) $selected = " selected"; else $selected = "";

		if ($a[4] == "0") {
			$category_sel .= '<option value="'.$cat_link.$a[2].$cat_z.'"'.$selected.'>'.$a[1].'</option>';
		}

	}

	$category_sel .= '</select>';

	$cat = isset($params['cat'])?$params['cat']:$_REQUEST['cat'];
	
    $limitCount = pluginGetVariable('avatar', 'av_per_page') ? pluginGetVariable('avatar', 'av_per_page') : '5';

    $pageNo		= intval($params['page'])?intval($params['page']):intval($_REQUEST['page']);
    if ($pageNo < 1)	$pageNo = 1;
    if (!$limitStart)	$limitStart = ($pageNo - 1)* $limitCount;

    $count = $mysql->result('SELECT COUNT(id) FROM '.prefix.'_avatars WHERE category = '. db_squote($cat).' ');

    if(!$count)
        return msg(array("type" => "error", "text" => "В данной категории нет загруженных аватаров"));


	$SYSTEM_FLAGS['info']['title']['group'] = 'Категория аватаров '.$cat.' '.$pageNo.'';

    $countPages = ceil($count / $limitCount);

    if(!is_array($userROW))
        return msg(array("type" => "error", "info" => "Просматривать список аватаров могут, только зарегестрированные пользователи."));

        if ($countPages > 1 && $countPages >= $pageNo){
            $paginationParams = checkLinkAvailable('avatar', '')?
                array('pluginName' => 'avatar', 'pluginHandler' => 'cat', 'params' => array('cat' => $cat?$cat:''), 'xparams' => array(), 'paginator' => array('page', 0, false)):
                array('pluginName' => 'core', 'pluginHandler' => 'plugin', 'params' => array('plugin' => 'avatar', 'handler' => 'cat'), 'xparams' => array(), 'paginator' => array('page', 1, false));

            $navigations = LoadVariabless();
            $pages = generatePagination($pageNo, 1, $countPages, 10, $paginationParams, $navigations);
        }
	

	foreach ($mysql->select('select * from '.prefix.'_avatars WHERE category = '. db_squote($cat).' ORDER BY id DESC LIMIT '.intval($limitStart).', '.intval($limitCount)) as $row){
		$list_avatar .= '<div align="center" style="width: 30%;float: left;padding:5px;">
				<a href="/avatar/ava_id/'.$row['id'].'/" title="Выбрать этот аватар">
					<img src="'.$http_avatars_dir.'/'.$row['category'].'/'.$row['av_name'].'">
				</a></div>';
	}
	
		$entries[] = array (
			'list_avatar'	=> $list_avatar,
		);


        if ($limitStart) {
            $prev = floor($limitStart / $limitCount);
            $PageLink = checkLinkAvailable('avatar', '')?
                generatePageLink(array('pluginName' => 'avatar', 'pluginHandler' => '', 'params' => array('' => $cat?$cat:''), 'xparams' => array(), 'paginator' => array('page', 0, false)), $prev):
                generatePageLink(array('pluginName' => 'core', 'pluginHandler' => 'plugin', 'params' => array('plugin' => 'avatar'), 'xparams' => array(), 'paginator' => array('page', 1, false)), $prev);

            $gvars['regx']["'\[prev-link\](.*?)\[/prev-link\]'si"] = str_replace('%page%',"$1",str_replace('%link%',$PageLink, $navigations['prevlink']));
        } else {
            $gvars['regx']["'\[prev-link\](.*?)\[/prev-link\]'si"] = "";
            $prev = 0;
        }

        if (($prev + 2 <= $countPages))
        {
            $PageLink = checkLinkAvailable('avatar', '')?
                generatePageLink(array('pluginName' => 'avatar', 'pluginHandler' => '', 'params' => array('' => $cat?$cat:''), 'xparams' => array(), 'paginator' => array('page', 0, false)), $prev+2):
                generatePageLink(array('pluginName' => 'core', 'pluginHandler' => 'plugin', 'params' => array('plugin' => 'avatar', 'handler' => ''), 'xparams' => array(), 'paginator' => array('page', 1, false)), $prev+2);
            $gvars['regx']["'\[next-link\](.*?)\[/next-link\]'si"] = str_replace('%page%',"$1",str_replace('%link%',$PageLink, $navigations['nextlink']));
        } else {
            $gvars['regx']["'\[next-link\](.*?)\[/next-link\]'si"] = "";
        }
	
	$tVars = array(
		'category_sel'  => $category_sel,
		'entries' => isset($entries)?$entries:'',
		'cat_avatar'	=> $cat,
            'pages' => array(
            'true' => (isset($pages) && $pages)?1:0,
            'print' => isset($pages)?$pages:''
                            ),
        'prevlink' => array(
                    'true' => !empty($limitStart)?1:0,
                    'link' => str_replace('%page%',
                                            "$1",
                                            str_replace('%link%',
                                                checkLinkAvailable('avatar', '')?
                generatePageLink(array('pluginName' => 'avatar', 'pluginHandler' => '', 'params' => array('' => $cat?$cat:''), 'xparams' => array(), 'paginator' => array('page', 0, false)), $prev = floor($limitStart / $limitCount)):
                generatePageLink(array('pluginName' => 'core', 'pluginHandler' => 'plugin', 'params' => array('plugin' => 'avatar', 'handler' => 'cat'), 'xparams' => array(), 'paginator' => array('page', 1, false)),$prev = floor($limitStart / $limitCount)),
                                                isset($navigations['prevlink'])?$navigations['prevlink']:''
                                            )
                    ),
        ),
        'nextlink' => array(
                    'true' => ($prev + 2 <= $countPages)?1:0,
                    'link' => str_replace('%page%',
                                            "$1",
                                            str_replace('%link%',
                                                checkLinkAvailable('avatar', '')?
                generatePageLink(array('pluginName' => 'avatar', 'pluginHandler' => '', 'params' => array('' => $cat?$cat:''), 'xparams' => array(), 'paginator' => array('page', 0, false)), $prev+2):
                generatePageLink(array('pluginName' => 'core', 'pluginHandler' => 'plugin', 'params' => array('plugin' => 'avatar', 'handler' => 'cat'), 'xparams' => array(), 'paginator' => array('page', 1, false)), $prev+2),
                                                isset($navigations['nextlink'])?$navigations['nextlink']:''
                                            )
                    ),
        ),
	);

	$template['vars']['mainblock'] = $xt->render($tVars);
}


function set_avatar($params) {
	global $tpl, $template, $twig, $mysql, $config, $SYSTEM_FLAGS, $userROW, $lang;
	
    $tpath = locatePluginTemplates(array('skins/avatars.list'), 'avatar', pluginGetVariable('avatar', 'localsource'), pluginGetVariable('avatar','localskin'));
    $xt = $twig->loadTemplate($tpath['skins/avatars.list'].'skins/avatars.list.tpl');
	
	if(!is_array($userROW))
       return msg(array("type" => "error", "info" => "Присвоить аватар могут, только зарегестрированные пользователи."));
   
	$av_name = isset($params['id'])?abs(intval($params['id'])):abs(intval($_REQUEST['id']));
	
	$row = $mysql->record('select * from '.prefix.'_avatars WHERE id = '. $av_name.' ORDER BY id DESC');
	
	$SYSTEM_FLAGS['info']['title']['group'] = 'Выбран аватар '.$row['av_name'].'';
	
	if (isset($_REQUEST['ava_ok'])){
		if (copy (avatars_dir ."/list/".$row['category'].'/'.$row['av_name'], avatars_dir .$row['av_name'])) {
			$mysql->query('UPDATE '.uprefix.'_users SET avatar='.db_squote($row['av_name']).' WHERE id='.$userROW['id'].'');
		}
		
		if (pluginGetVariable('avatar', 'av_serv_del')) {
			if (file_exists (avatars_dir ."/list/".$row['category'].'/'.$row['av_name'])) {
				$mysql->query('delete from '.prefix.'_avatars where id = '.db_squote($av_name).'');
				unlink (avatars_dir ."/list/".$row['category'].'/'.$row['av_name']);
			}
		}
		return msg(array("type" => "info", "text" => 'Аватар успешно установлен!<br><a href=/>Вернуться</a><br>'));
	}
	
	$tVars = array(
		'avatar'          => '/uploads/avatars/list/'.$row['category'].'/'.$row['av_name'],
		'avaname' => $row['av_name'],
	);
	
	if ($row['av_name'] != ''){
		$template['vars']['mainblock'] = $xt->render($tVars);
	}else{
		$template['vars']['mainblock'] = 'Аватар не найден';
	}
}

function LoadVariabless()
{
	$tpath = locatePluginTemplates(array(':'), 'avatar', pluginGetVariable('avatar', 'localsource'), pluginGetVariable('avatar', 'localskin'));
	return parse_ini_file($tpath[':'] . '/skins/variables.ini', true);

}


LoadPluginLibrary('uprofile', 'lib');

class AvatarUserFilter extends p_uprofileFilter {

	function editProfileForm($userID, $SQLrow, &$tvars) {
		global $lang, $template, $mysql, $twig;
		
		$tvars['user']['avatar_user'] = '[<a href="/avatar/" title="Выбрать аватар">выбрать аватар из списка</a>]';
	
	}
}

pluginRegisterFilter('plugin.uprofile', 'avatar', new AvatarUserFilter);
?>