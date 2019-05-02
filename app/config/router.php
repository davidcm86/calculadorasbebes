<?php

$router = $di->getRouter();

$router->add(
    '/{language:[a-z]{2}}/{slug}',
    [
        'controller' => 'calculadoras',
        'action'     => 'index',
    ]
);

$router->add("/{language:[a-z]{2}}", array(
    "lang" => 1
));

$router->handle();
