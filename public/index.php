<?php
$app = require_once  __DIR__ . '/../app.php';

/** @var $app \Silex\Application */

/* Detect language by accept-language header */
$app->before(function() use ($app){
	// i18n
	
	// Read avaliable locales
	$localeFiles = glob(__DIR__ . '/../resources/i18n/*.php');
	$locales = array();
	foreach($localeFiles as $file) {
		$locales[] = basename($file, ".php");
	}
	
	// Set locale according to HTTP Accept-Language Header
	/** @var $request \Symfony\Component\HttpFoundation\Request */
	$request = $app['request'];
	$locale = $request->getPreferredLanguage($locales);
	
	/** @var $i18n \Symfony\Component\Translation\Translator */
	$i18n = $app['translator'];
	$i18n->setLocale($locale);
});

/* Routing */
$app->get('/index.html', 'controllers.helloworld:sayHello');
$app->get('/', 'controllers.helloworld:sayHello');
$app->get('/rest/api/1/messages', 'controllers.messages:index');

$app->run();