<?php
define('BASE_PATH', __DIR__);
define('SOURCE_DIR', BASE_PATH . "/src");

$classloader = require_once __DIR__.'/vendor/autoload.php';
$app = new Silex\Application();

/* Providers */
$env = getenv('APPLICATION_ENV') ?: 'config';
$app->register(new Igorw\Silex\ConfigServiceProvider(__DIR__ . "/resources/config/$env.php"));
$app->register(new Silex\Provider\ServiceControllerServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), array(
		'twig.path' => __DIR__ . '/resources/views'
));

/* ORM Setup */
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => $app['db.options']
));
$app->register(new Dflydev\Silex\Provider\DoctrineOrm\DoctrineOrmServiceProvider(), array(
    'orm.proxies_dir' => __DIR__ . "/resources/cache/doctrine/proxy",
    'orm.em.options' => array(
        "mappings" => array(
            array(
                'type' => 'annotation',
                'namespace' => 'SilexSkeleton\Entity',
                'path' => __DIR__ . "/src/SilexSkeleton/Entity"
            )
        )
    )
));
Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(array($classloader, 'loadClass'));

// Create SQLite database if not exists
if(isset($app['db.options']['driver']) && $app['db.options']['driver'] === 'pdo_sqlite') {
	if(isset($app['db.options']['path']) && (!file_exists($app['db.options']['path']) || filesize($app['db.options']['path']) === 0 )) {
		putenv("APPLICATION_ENV=" . $env);
		chdir(__DIR__);
		$result = null;
		system("php vendor/doctrine/orm/bin/doctrine.php orm:schema-tool:create", $result);
		if($result !== 0) {
			echo "<h1>Error creating SQLite Demo Database!</h1>";
			echo "Could not run 'php vendor/doctrine/orm/bin/doctrine.php orm:schema-tool:create'.<br><br>";
			echo "Please ensure that the php executable is in your PATH and refresh the page or run the command above manually!";
		} else {
			header('Refresh:0');
		}
		exit();
	}
}

/* i18n support */
$app->register(new Silex\Provider\TranslationServiceProvider());
$app['locales'] = $app->share(function() use ($app){
	// Read avaliable locales
	$localeFiles = glob(__DIR__ . '/resources/i18n/*.php');
	$locales = array();
	foreach($localeFiles as $file) {
		$locale = basename($file, ".php");
		$locales[$locale] = $file;
	}
	
	return $locales;
});
$app['translator'] = $app->share($app->extend('translator', function($translator, $app) {
	foreach($app['locales'] as $locale => $file) {
		$translator->addResource('array', require $file, $locale);
	}
	
    return $translator;
}));
$app['twig']->addExtension(new Symfony\Bridge\Twig\Extension\TranslationExtension($app['translator']));

/* Dependency Injection wiring */
$app['service.messages'] = $app->share(function() use ($app){
	return new SilexSkeleton\Service\Impl\MessageServiceImpl($app['orm.em']);
});

$app['controllers.helloworld'] = $app->share(function() use ($app){
	return new SilexSkeleton\Controller\HelloWorldController($app['twig']);
});

$app['controllers.messages'] = $app->share(function() use ($app){
	return new SilexSkeleton\Controller\MessageController($app['service.messages']);
});

return $app;
