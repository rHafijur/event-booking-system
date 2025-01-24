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
        $GLOBALS['csrf_token'] = CsrfProtection::generateToken();
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
        $GLOBALS['csrf_token'] = CsrfProtection::generateToken();
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
    'GET /event/{id}' => function (array $params): void {
        applyMiddleware([AuthenticatedMiddleware::class], function () use ($params): void {
            $controller = ControllerFactory::getEventController();
            $controller->details($params['id']);
        });
    },
];

// Process the Request
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = strtok($_SERVER['REQUEST_URI'], '?');

// Match the Route with Parameters
function matchRoute(string $method, string $uri, array $routes)
{
    foreach ($routes as $route => $callback) {
        [$routeMethod, $routePattern] = explode(' ', $route, 2);

        if ($method !== $routeMethod) {
            continue;
        }

        // Convert route patterns to regex
        $pattern = preg_replace('#\{(\w+)\}#', '(?P<$1>[^/]+)', $routePattern);
        $pattern = "#^$pattern$#";

        if (preg_match($pattern, $uri, $matches)) {
            return [$callback, array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY)];
        }
    }

    return [null, []];
}

[$callback, $params] = matchRoute($requestMethod, $requestUri, $router);

if ($callback) {
    $callback($params);
} else {
    http_response_code(404);
    echo '404 - Page Not Found';
}
