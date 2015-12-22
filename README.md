# Silex Skeleton

This is a basic SILEX Skeleton setup the way I personaly prefer it. This project
serves as a starting point for new Silex applications.

- igorw's `config-service-provider` to load simple PHP array configs 
- dflydev's `doctrine-orm-service-provider` for easy database access and ORM abstraction
- symfony's translation module for translations
- symfony's twig bridge as HTML template language

It also includes a simple demo app that shows you how ...

- ... to use controllers as seperate classes 
- ... HTML and JSON controllers work
- ... routing works 
- ... doctrine orm database access works
- ... internationalization of your app works
- ... REST works
- ... exception handling works

## Getting started

### Prerequisites

You must have [composer](https://getcomposer.org) and [git](https://git-scm.com) installed on your system.

### Creating a new project

1. Open a command line interface
2. run `composer create-project Takuto88/silex-skeleton path/to/install`. Composer will create a folder for your and install the new project. 
3. [Configure your system and webserver](#sysconfig)
4. Assuming you have configured your webserver to `silex-demo.dev`, navigate to http://silex-demo.dev
5. Enjoy! You can always read a [how-to](#howto) in order to proceed.

### <a name="sysconfig"></a> System and webserver config

1. Setup your webserver's virtual host. See [Apache example](#apache-example) if you're using apache.
2. Edit your hosts-file (Windows: `C:\Windows\System32\drivers\etc\hosts`) (Unix-like systems: `/etc/hosts`) to include the following line: `127.0.0.1  [your-servername]`. If you want your project to be avaliable unter http://silex-demo.dev the line must look like: `127.0.0.1  silex-demo.dev`. Be sure that your Webserver's virtual host ServerName listens on that name! See [Apache example](#apache-example) if you're stuck
3. Reload your webserver configuration or just restart your webserver.
 
#### <a name="apache-example"></a> Apache Example config

**Don't blindly copy & paste! READ THE COMMENTS!**

In your `httpd-vhosts.conf`, include the follwing directive:

```
<VirtualHost *:80>
  ServerName silex-demo.dev # Must match your hosts-file name!
  DocumentRoot "/path/to/your/project/public" # Be sure to point to the /public folder in your project!
  
  <Directory /path/to/your/project>
    Options Indexes FollowSymLinks MultiViews
    AllowOverride All # Not recommended on production systems
    
    # Apache 2.4 and greater
    Require all granted
    
    # Apache 2.2: Use this instead
    #Order allow,deny
    #allow from all
  </Directory>
  
  # Optional - Use this to use 'development.php' as your config-file.
  # Silex skeleton will default to 'config.php' if APPLICATION_ENV is undefined.
  # SetEnv APPLICATION_ENV development

  # Log files
  CustomLog "logs/silex-access_log" combined
  ErrorLog "logs/silex-error_log"
</VirtualHost>
```

## How to

### Use a different config-file

Configs are located in `/resources/config`. Assuming you would like to use `local_devconfig.php` instead of the default `config.php`, then do this:

You must set the `APPLICATION_ENV` environment variable to `local_devconfig`. This can be done in the following ways:

**Apache**

Add `SetEnv APPLICATION_ENV local_devconfig` to 

- `public/.htaccess` OR
- in your `httpd-vhosts.conf`

If you go for your vhosts config file, don't forget to reload the webserver configuration or to restart your webserver. 

**Other webservers** 

I'm old, I just use Apache as it is good enough for me :smile:. Feel free to send a pull request.

### Use MySQL instead of SQLite

Look at `resources/config.php.dist` for an example. For reference, I'll include that here.

```php
<?php 

return array(
        'debug' => false,
        'db.options' => array(
                'driver' => 'pdo_mysql',
                'dbname' => 'yourdb',
                'host' => '127.0.0.1',
                'user' => 'root',
                'password' => 'secret',
                'charset' => 'utf8',
                'port' => '3306',
        ),
        'db.orm.options' => array(
                'orm.default_cache' => 'apc'
        )
    
);
```

### Use a different namespace

It is highly encouraged, that you use namespaces for your projects. Assuming you would like to add the namespace named `MyApp`:

- Create a folder called "MyApp" under '/src'
- If you are going to use doctrine for your database mapping, you must make it aware of your entities. Edit the `app.php` and look for: 
```php
<?php
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
```
- Add your mapping config to the mappings array. See [the doctrine orm service provider documentation](https://github.com/dflydev/dflydev-doctrine-orm-service-provider#parameters) for more details.

And you're done. Of course you can delete `src/SilexSkeleton` if you have no more use for it. It simply serves as an example on how things work.

### How do I add a route and or controller?

I am assuming we are using the SilexSkeleton namespace for now. This is an example of what's needed:

**Controller: /src/SilexSkeleton/Controller/MyNewController.php**
```php
<?php 
namespace SilexSkeleton\Controller;

class MyNewController {
  
  // Assuming you want to use twig: inject it.
  private $twig;
  
  public function __construct(\Twig_Environment $twig) {
		$this->twig = $twig;
	}
  
  public function doMyAction() {
    return $this->twigEnv->render("myaction.html", array());
  }
  
}
```

**Template: /resources/views/myaction.html**
```
{% extends "layout.html" %}

{% block content %}
<h1>This is SilexSkeleton\Controller\MyNewController::doMyAction()</h1>
{% endblock %}
```

**Depenendy injection wiring: /app.php**
```php
<?php
// [...]
$app['controllers.mynewcontoller'] = $app->share(function() use ($app){
	return new SilexSkeleton\Controller\MyNewController($app['twig']);
});
// [...]
```

**Route: /public/index.php**
```php
<?php
$app->get('/myaction', 'controllers.mynewcontroller:doMyAction');
```

And that's all. Go to http://silex-demo.dev/mynewaction (or whatever your VHost is) in order to see what happens.

### Add a different language

Just add a new translation file to '/resources/i18n' and name it accordingly. For instance:

- **da.php** Would be for Danish.
- **fr.php** Would be for French.

You can also add more specific files:
- **de-de.php** Would be for German (Germany)
- **de-au.php** Would be for German (Austria)

The files itsef must look like this:

```php
<?php

return array(
  'messageKey' => 'Localized value to show to the user'
);
```

## Doctrine ORM pointers

As the database abstraction layer is handled by Doctrine, you should make yourself familiar with the doctrine anotations in order to create your database schema. The [annotation documentation](http://doctrine-common.readthedocs.org/en/latest/reference/annotations.html) will help you out.

Also, the doctrine command line tool is very useful. Just run `php /vendor/bin/doctrine`. That script is broken if you are using Windows. Run `php /vendor/doctrine/orm/bin/doctrine.php` instead and it will work fine. [Here is some documentation](http://doctrine-orm.readthedocs.org/projects/doctrine-orm/en/latest/reference/tools.html) on the tool itself. 

Don't be afraid of Doctrine! It is actually very simple and once you've grasped it, you don't ever want to go back to plain SQL anymore.
