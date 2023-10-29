<?php

//$router->get('/', 'index.php');

$uri = $uri = parse_url($_SERVER['REQUEST_URI'])['path'];

$routes = [
    '/' => 'controllers/testing_controller.php',
    '/testing2' => 'controllers/testing_controller2.php'
    // '/more_routes' => 'controllers/controller.php'
];

function route_to_controller($uri, $routes){
    if(array_key_exists($uri,$routes)){  //If uri exist in routes
        require $routes[$uri];          //do this
    }else {
        cringe();
    }
}

function cringe(){
    http_response_code(404);

    require 'views/404.php';

    die();
}

route_to_controller($uri, $routes);