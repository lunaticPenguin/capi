<?php

return new \Phalcon\Config(array(
    'database' => array(
        'adapter'     => 'Mysql',
        'host'        => 'localhost',
        'username'    => 'root',
        'password'    => '',
        'dbname'      => 'test',
    ),
    'application' => array(
        'maintenance'   => false,
        'registeredNS' => array(
            'App\Models' => __DIR__ . '/../../app/models/',
            'App\Controllers' => __DIR__ . '/../../app/controllers/',
            'App\Plugins' => __DIR__ . '/../../app/plugins/',
        ),
        'baseUri'        => '/'
    )
));
