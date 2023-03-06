<?php
if (!defined('NGCMS'))
{
	die ('HAL');
}

LoadPluginLang('avatar', 'config', '', '', '#');

include_once(root . "/plugins/avatar/lib/common.php");

pluginsLoadConfig();
function plugin_avatar_install($action) {
	global $lang, $mysql;
	
    if(!file_exists(dirname(dirname(dirname(dirname(__FILE__)))).'/uploads/avatars/list'))
        if(!@mkdir(dirname(dirname(dirname(dirname(__FILE__)))).'/uploads/avatars/list/', 0777))
            msg(array("type" => "error", "text" => "".$lang['avatar:folder_error_list']."".dirname(dirname(dirname(dirname(__FILE__)))).'/uploads/avatars/list'), 1);

	if ($action != 'autoapply')
	
	$db_update = array(
		array(
			'table'  => 'avatars',
			'action' => 'cmodify',
			'key'    => 'primary key (id)',
			'fields' => array(
				array('action' => 'cmodify', 'name' => 'id', 'type' => 'int(11)', 'params' => 'NOT NULL AUTO_INCREMENT'),
				array('action' => 'cmodify', 'name' => 'av_name', 'type' => 'text', 'params' => 'NOT NULL'),
				array('action' => 'cmodify', 'name' => 'author', 'type' => 'varchar(40)', 'params' => 'NOT NULL'),
				array('action' => 'cmodify', 'name' => 'category', 'type' => 'varchar(200)', 'params' => 'NOT NULL'),
				array('action' => 'cmodify', 'name' => 'time', 'type' => 'int(10)', 'params' => 'NOT NULL DEFAULT \'0\'')
			)
		)
	);
	
	switch ($action) {
		case 'confirm':
			generate_install_page('avatar', $lang['avatar']['install']);
			break;
		case 'autoapply':
		case 'apply':
			if (fixdb_plugin_install('avatar', $db_update, 'install', ($action == 'autoapply') ? true : false)) {
				plugin_mark_installed('avatar');
				create_avatar_urls();
			} else {
				return false;
			}
			
            extra_commit_changes();
			
			break;
	}

	return true;
}