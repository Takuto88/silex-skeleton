<?php 
/*
 * Example configuration using SQLite
 */
return array(
        'debug' => true,
        'db.options' => array(
                'driver' => 'pdo_sqlite',
                'path' => BASE_PATH. '/resources/database.sqlite'
        ),
        'db.orm.options' => array(
                'orm.default_cache' => 'apc'
        )
    
);