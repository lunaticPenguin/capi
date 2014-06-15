<?php

$objRouter = new Phalcon\Mvc\Router();

/**
 * Define all routes here
 */

$objRouter->add('/{controller:[a-z\-]+}/{action:[a-z\-]+}{params:[a-z0-9\?\/_-]*$}', array(
    'namespace'	=>	'App\Controllers',
    'controller' => 1,
    'action' => 2,
    'params' => 3
))
->setName('public');

$objRouter->handle();
return $objRouter;