<?php

$router = $di->getRouter();

/*$router->add('/{language:[a-z]{2}}', array(
    'controller' => 'index',
    'action' => 'index'
));

$router->add('/{language:[a-z]+}/:controller/:action', array(
    'controller' => 2,
    'action' => 3
));*/

$router->add("/{language:[a-z]{2}}", array(
    "lang" => 1
  ));

/*$router->add('/{language:[a-z]+}/:controller/:action/:params', array(
    'controller' => 2,
    'action' => 3,
    'params' => 4
));*/

$router->handle();
