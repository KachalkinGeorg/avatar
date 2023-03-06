<?php
// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

LoadPluginLang('avatar', 'config', '', '', '#');

include_once(root . "/plugins/avatar/lib/common.php");

$db_update = array(
	array(
		'table'  => 'avatars',
		'action' => 'drop',
	)
);

if ($_REQUEST['action'] == 'commit') {
	if (fixdb_plugin_install('avatar', $db_update, 'deinstall')) {
		plugin_mark_deinstalled('avatar');
	}
	remove_avatar_urls();
} else {
	$text = 'Cейчас плагин будет удален.<br>Внимание!<br>При удалении плагина, картинки загруженные на сервер остануться не тронутыми.<br> Вы уверены?';
	generate_install_page('avatar', $text, 'deinstall');
}