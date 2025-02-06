<?php

use lmy\humhub\modules\bbd\Events;
use humhub\modules\admin\widgets\AdminMenu;
use humhub\modules\space\widgets\Menu;


return [
	'id' => 'bbd',
	'class' => 'lmy\humhub\modules\bbd\Module',
	'namespace' => 'lmy\humhub\modules\bbd',
	'events' => [
		[
			'class' => Menu::class,
			'event' => Menu::EVENT_INIT,
			'callback' => [Events::class, 'onSpaceMenuInit'],
		],
		[
			'class' => AdminMenu::class,
			'event' => AdminMenu::EVENT_INIT,
			'callback' => [Events::class, 'onAdminMenuInit']
		],
		[
			'class' => 'humhub\modules\rest\Module', 
			'event' => 'restApiAddRules', 
			'callback' => [Events::class, 'onRestApiAddRules']
		],
	],
];
