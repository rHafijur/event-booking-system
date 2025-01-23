<?php

use App\Factories\ControllerFactory;
use App\Middlewares\AuthenticatedMiddleware;
use App\Middlewares\AdminMiddleware;
use App\Middlewares\CsrfProtection;

function applyMiddleware(array $middlewares, callable $callback)
{
    foreach ($middlewares as $middleware) {
        $instance = new $middleware();
        $instance->handle();
    }

    call_user_func($callback);
}

// Routes
$router = [
    // User Authentication
    'GET /login' => function (): void {
        $controller = ControllerFactory::getUserController();
        $controller->loginView();
    },
    'POST /login' => function (): void {
        applyMiddleware([CsrfProtection::class], function (): void {
            $controller = ControllerFactory::getUserController();
            $controller->login();
        });
    },
    'GET /logout' => function (): void {
        applyMiddleware([AuthenticatedMiddleware::class], function (): void {
            $controller = ControllerFactory::getUserController();
            $controller->logout();
        });
    },
    'GET /register' => function (): void {
        $controller = ControllerFactory::getUserController();
        $controller->registerView();
    },
    'POST /register' => function (): void {
        applyMiddleware([CsrfProtection::class], function (): void {
            $controller = ControllerFactory::getUserController();
            $controller->register();
        });
    },

    // Event Management
    'GET /events' => function (): void {
        applyMiddleware([AuthenticatedMiddleware::class], function (): void {
            $controller = ControllerFactory::getEventController();
            $controller->list();
        });
    },
    'GET /event/create' => function (): void {
        applyMiddleware([AuthenticatedMiddleware::class], function (): void {
            $controller = ControllerFactory::getEventController();
            $controller->createView();
        });
    },
    'POST /event/create' => function (): void {
        applyMiddleware([AuthenticatedMiddleware::class, CsrfProtection::class], function (): void {
            $controller = ControllerFactory::getEventController();
            $controller->create();
        });
    },
];


$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = strtok($_SERVER['REQUEST_URI'], '?');
$routeKey = "$requestMethod $requestUri";

if (array_key_exists($routeKey, $router)) {
    $router[$routeKey]();
} else {
    http_response_code(404);
    echo '404 - Page Not Found';
}
