<?php
use Symfony\Component\HttpFoundation\JsonResponse;
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

// REST
$app->get('/rest/api/1/messages', 'controllers.messages:index');
$app->get('/rest/api/1/messages/{id}', 'controllers.messages:get');

/* Exception handling */
$app->error(function (\Exception $e, $code) use ($app){
	/** @var $request \Symfony\Component\HttpFoundation\Request */
	$request = $app['request'];
	
	// REST Request? Return as JSON.
	if(strpos($request->getUri(), "/rest/api/1")) {
		$message = $app['translator']->trans($e->getMessage());
		$response = array(
				'message' => $message
		);
		
		return new JsonResponse($response, $code);
	}
		
	return $app['twig']->render("error.html", array('code' => $code, 'message' => $app['translator']->trans($e->getMessage())));
});

$app->run();