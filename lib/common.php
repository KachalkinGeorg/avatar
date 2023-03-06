<?php


function create_avatar_urls()
{

 			$ULIB = new urlLibrary();
			$ULIB->loadConfig();
			$ULIB->registerCommand('avatar', '',
				array ('vars' =>
						array(),
						'descr'	=> array ('russian' => 'Главная страница'),
				)
			);
			$ULIB->registerCommand('avatar', 'cat',
				array ('vars' =>
						array( 	'cat' => array('matchRegex' => '.+?', 'descr' => array('russian' => 'Категории')),
						'page' => array('matchRegex' => '\d{1,4}', 'descr' => array('russian' => 'Постраничная навигация'))
						),
						'descr'	=> array ('russian' => 'Главная страница'),
				)
			);
			$ULIB->registerCommand('avatar', 'ava_id',
				array ('vars' =>
						array(	'id' => array('matchRegex' => '\d+', 'descr' => array('russian' => 'Выбор аватара')),
						),
						'descr'	=> array ('russian' => 'Выбор аватара пользователя'),
				)
			);
			$ULIB->saveConfig();
			
			$UHANDLER = new urlHandler();
			$UHANDLER->loadConfig();
			$UHANDLER->registerHandler(0,
				array (
				'pluginName' => 'avatar',
				'handlerName' => '',
				'flagPrimary' => true,
				'flagFailContinue' => false,
				'flagDisabled' => false,
				'rstyle' => 
				array (
				  'rcmd' => '/avatar/',
				  'regex' => '#^/avatar/$#',
				  'regexMap' => 
				  array (
				  ),
				  'reqCheck' => 
				  array (
				  ),
				  'setVars' => 
				  array (
				  ),
				  'genrMAP' => 
				  array (
					0 => 
					array (
					  0 => 0,
					  1 => '/avatar/',
					  2 => 0,
					),
				  ),
				),
			  )
			);
			$UHANDLER->registerHandler(0,
				array (
				'pluginName' => 'avatar',
				'handlerName' => 'cat',
				'flagPrimary' => true,
				'flagFailContinue' => false,
				'flagDisabled' => false,
				'rstyle' => 
				array (
				  'rcmd' => '/avatar/cat/[cat/{cat}/][page/{page}/]',
				  'regex' => '#^/avatar/cat/(.+?)/(?:page/(\\d{1,4})/){0,1}$#',
				  'regexMap' => 
				  array (
					1 => 'cat',
					2 => 'page',
				  ),
				  'reqCheck' => 
				  array (
				  ),
				  'setVars' => 
				  array (
				  ),
				  'genrMAP' => 
				  array (
					0 => 
					array (
					  0 => 0,
					  1 => '/avatar/',
					  2 => 0,
					),
					1 => 
					array (
					  0 => 0,
					  1 => 'cat/',
					  2 => 1,
					),
					2 => 
					array (
					  0 => 1,
					  1 => 'cat',
					  2 => 1,
					),
					3 => 
					array (
					  0 => 0,
					  1 => '/',
					  2 => 1,
					),
					4 => 
					array (
					  0 => 0,
					  1 => 'page/',
					  2 => 3,
					),
					5 => 
					array (
					  0 => 1,
					  1 => 'page',
					  2 => 3,
					),
					6 => 
					array (
					  0 => 0,
					  1 => '/',
					  2 => 3,
					),
				  ),
				),
			  )
			);
			$UHANDLER->registerHandler(0,
				array (
				'pluginName' => 'avatar',
				'handlerName' => 'ava_id',
				'flagPrimary' => true,
				'flagFailContinue' => false,
				'flagDisabled' => false,
				'rstyle' => 
				array (
				  'rcmd' => '/avatar/ava_id/{id}/',
				  'regex' => '#^/avatar/ava_id/(\\d+)/$#',
				  'regexMap' => 
				  array (
					1 => 'id',
				  ),
				  'reqCheck' => 
				  array (
				  ),
				  'setVars' => 
				  array (
				  ),
				  'genrMAP' => 
				  array (
					0 => 
					array (
					  0 => 0,
					  1 => '/avatar/ava_id/',
					  2 => 0,
					),
					1 => 
					array (
					  0 => 1,
					  1 => 'id',
					  2 => 0,
					),
					2 => 
					array (
					  0 => 0,
					  1 => '/',
					  2 => 0,
					),
				  ),
				),
			  )
			);
    $UHANDLER->saveConfig();
}

function remove_avatar_urls()
{
    $ULIB = new urlLibrary();
    $ULIB->loadConfig();
    $ULIB->removeCommand('avatar', '');
	$ULIB->removeCommand('avatar', 'cat');
	$ULIB->removeCommand('avatar', 'ava_id');
    $ULIB->saveConfig();
    $UHANDLER = new urlHandler();
    $UHANDLER->loadConfig();
    $UHANDLER->removePluginHandlers('avatar', '');
	$UHANDLER->removePluginHandlers('avatar', 'cat');
	$UHANDLER->removePluginHandlers('avatar', 'ava_id');
    $UHANDLER->saveConfig();
}
