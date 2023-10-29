<?php

//$router->get('/', 'index.php');

$uri = $uri = parse_url($_SERVER['REQUEST_URI'])['path'];

$routes = [
    '/' => 'controllers/testing_controller.php',
    '/testing2' => 'controllers/testing_controller2.php'
    // '/more_routes' => 'controllers/controller.php'
];

function route_to_controller($uri, $routes)
{
    if(array_key_exists($uri,$routes))
    {                                   //If uri exist in routes
        require $routes[$uri];          //do this
    }
    else 
    {
        cringe();
    }
}

function cringe($code = 404){
    http_response_code($code);

    require "views/{$code}.php";

    die();
}

route_to_controller($uri, $routes);

