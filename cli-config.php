<?php

// Doctrine CLI configuration

require __DIR__ . '/vendor/autoload.php';

define('BASE_PATH', __DIR__);

$env = getenv('APPLICATION_ENV');

if(!$env) {
	echo "*** WARNING ***\n";
    echo "Please specify your APPLICATION_ENV variable which is used to determaine what config to use!\n";
    echo "On Unix-like systems, run 'export APPLICATION_ENV=foo' to achive this.\n\n";
    echo "Defaulting to 'config'.\n";
    
    $env = 'config';
}

$app = new Silex\Application();
$app->register(new Igorw\Silex\ConfigServiceProvider(BASE_PATH . "/resources/config/$env.php"));
$newDefaultAnnotationDrivers = array(
       __DIR__ . "/src/"
);

$config = new \Doctrine\ORM\Configuration();
$config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ArrayCache());

$driverImpl = $config->newDefaultAnnotationDriver($newDefaultAnnotationDrivers);
$config->setMetadataDriverImpl($driverImpl);

$config->setProxyDir(__DIR__ . "/resources/cache/doctrine/proxy");
$config->setProxyNamespace("Proxies");

$em = \Doctrine\ORM\EntityManager::create($app['db.options'], $config);

$helpers = new Symfony\Component\Console\Helper\HelperSet(array(
        'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
        'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em),
));