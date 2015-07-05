<?php

use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Database\Type;
use Croogo\Blocks\Catalog as BlocksCatalog;
use Croogo\Core\Croogo;

// Map our custom types
Type::map('params', 'Croogo\Core\Database\Type\ParamsType');
Type::map('encoded', 'Croogo\Core\Database\Type\EncodedType');
Type::map('link', 'Croogo\Core\Database\Type\LinkType');

Configure::write(
	'DebugKit.panels',
	array_merge((array)Configure::read('DebugKit.panels'), ['Croogo/Core.Plugins'])
);

BlocksCatalog::register('Croogo/Core.BlogFeed', [
	'title' => 'Croogo blog feed',
	'description' => 'Shows the Croogo blog feed',
	'regions' => [
		'dashboard' => []
	],
	'regionAliases' => [
		'right' => 'dashboard',
		'sidebar' => 'dashboard',
	]
]);

require_once 'croogo_bootstrap.php';

if (Configure::read('Croogo.installed')) {
	return;
}

// Load Install plugin
if (Configure::read('Security.salt') == 'f78b12a5c38e9e5c6ae6fbd0ff1f46c77a1e3' ||
	Configure::read('Security.cipherSeed') == '60170779348589376') {
	$_securedInstall = false;
}
Configure::write('Install.secured', !isset($_securedInstall));
Configure::write('Croogo.installed',
	file_exists(APP . 'config' . DS . 'database.php') &&
	file_exists(APP . 'config' . DS . 'settings.json') &&
	file_exists(APP . 'config' . DS . 'croogo.php')
);
if (!Configure::read('Croogo.installed') || !Configure::read('Install.secured')) {
	Plugin::load('Croogo/Install', ['routes' => true, 'path' => Plugin::path('Croogo/Core') . '..' . DS . 'Install' . DS]);
}
