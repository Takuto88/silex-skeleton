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

$baseNamespace = "SilexSkeleton";

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
                'namespace' =>  $baseNamespace . '\Entity',
                'path' => __DIR__ . "/src/" . $baseNamespace . "/Entity"
            )
        )
    )
));

Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(array($classloader, 'loadClass'));

/* i18n support */
$app->register(new Silex\Provider\TranslationServiceProvider());
$app['translator'] = $app->share($app->extend('translator', function($translator, $app) {
    $translator->addResource('array', require __DIR__ . '/resources/i18n/de.php', 'de');
    $translator->addResource('array', require __DIR__ . '/resources/i18n/en.php', 'en');
    
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
