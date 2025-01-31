<?php

use Infrastructure\Container;
use App\Factories\SeederFactory;
use App\Middlewares\CsrfProtection;
use App\Factories\ControllerFactory;
use App\Middlewares\AuthenticatedMiddleware;
use Infrastructure\Database\Migrations\CreateUsersTable, Infrastructure\Database\Migrations\CreateEventsTable, Infrastructure\Database\Migrations\CreateAttendeesTable;

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
    // This route is to run the db migration, GUID for simple security 😇
    'GET /migrate/3e5b4559-508f-4daa-b790-928740657bd7' => function (): void {
        CreateUsersTable::up();
        CreateEventsTable::up();
        CreateAttendeesTable::up();
    },
    // This route is to seed the users, GUID for simple security 😇
    'GET /db-seed/3e5b4559-508f-4daa-b790-928740657bd7' => function (): void {
        $seeder = SeederFactory::getUserSeeder();
        Container::getInstance()->call($seeder, 'run');
    },
    'GET /' => function (): void {
        $controller = ControllerFactory::getLandingPageController();
        Container::getInstance()->call($controller, 'index');
    },
    // User Authentication
    'GET /login' => function (): void {
        $controller = ControllerFactory::getUserController();
        Container::getInstance()->call($controller, 'loginView');
    },
    'POST /login' => function (): void {
        applyMiddleware([CsrfProtection::class], function (): void {
            $controller = ControllerFactory::getUserController();
            Container::getInstance()->call($controller, 'login');
        });
    },
    'GET /logout' => function (): void {
        applyMiddleware([AuthenticatedMiddleware::class], function (): void {
            $controller = ControllerFactory::getUserController();
            Container::getInstance()->call($controller, 'logout');
        });
    },
    'GET /register' => function (): void {
        $controller = ControllerFactory::getUserController();
        Container::getInstance()->call($controller, 'registerView');
    },
    'POST /register' => function (): void {
        applyMiddleware([CsrfProtection::class], function (): void {
            $controller = ControllerFactory::getUserController();
            Container::getInstance()->call($controller, 'register');
        });
    },

    'GET /dashboard' => function (): void {
        applyMiddleware([AuthenticatedMiddleware::class], function (): void {
            $controller = ControllerFactory::getDashboardController();
            Container::getInstance()->call($controller, 'index');
        });
    },

    // Event Management
    'GET /events' => function (): void {
        applyMiddleware([AuthenticatedMiddleware::class], function (): void {
            $controller = ControllerFactory::getEventController();
            Container::getInstance()->call($controller, 'list');
        });
    },
    'GET /event/create' => function (): void {
        applyMiddleware([AuthenticatedMiddleware::class], function (): void {
            $controller = ControllerFactory::getEventController();
            Container::getInstance()->call($controller, 'createView');
        });
    },
    'POST /event/create' => function (): void {
        applyMiddleware([AuthenticatedMiddleware::class, CsrfProtection::class], function (): void {
            $controller = ControllerFactory::getEventController();
            Container::getInstance()->call($controller, 'create');
        });
    },
    'GET /event/{id}' => function (array $params): void {
        applyMiddleware([AuthenticatedMiddleware::class], function () use ($params): void {
            $controller = ControllerFactory::getEventController();
            Container::getInstance()->call($controller, 'details', ['eventId' => $params['id']]);
        });
    },
    'GET /event/{id}/download-attendees-report' => function (array $params): void {
        applyMiddleware([AuthenticatedMiddleware::class], function () use ($params): void {
            $controller = ControllerFactory::getEventController();
            Container::getInstance()->call($controller, 'downloadAttendeesReport', ['eventId' => $params['id']]);
        });
    },
    'GET /event/{id}/edit' => function (array $params): void {
        applyMiddleware([AuthenticatedMiddleware::class], function () use ($params): void {
            $controller = ControllerFactory::getEventController();
            Container::getInstance()->call($controller, 'edit', ['id' => $params['id']]);
        });
    },
    'POST /event/{id}/update' => function (array $params): void {
        applyMiddleware([AuthenticatedMiddleware::class, CsrfProtection::class], function () use ($params): void {
            $controller = ControllerFactory::getEventController();
            Container::getInstance()->call($controller, 'update', ['eventId' => $params['id']]);
        });
    },
    'POST /event/{id}/delete' => function (array $params): void {
        applyMiddleware([AuthenticatedMiddleware::class, CsrfProtection::class], function () use ($params): void {
            $controller = ControllerFactory::getEventController();
            Container::getInstance()->call($controller, 'delete', ['eventId' => $params['id']]);
        });
    },
    'GET /event/{id}/register' => function (array $params): void {
        $controller = ControllerFactory::getAttendeeController();
        Container::getInstance()->call($controller, 'registerView', ['eventId' => $params['id']]);
    },
    'POST /attendee/register' => function (array $params): void {
        applyMiddleware([CsrfProtection::class], function () use ($params): void {
            $controller = ControllerFactory::getAttendeeController();
            Container::getInstance()->call($controller, 'register');
        });
    },
];

require_once __DIR__.'/api.php';

// Process the Request
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = strtok($_SERVER['REQUEST_URI'], '?');

if($requestMethod == 'GET'){
    $GLOBALS['csrf_token'] = CsrfProtection::generateToken();
}

// Match the Route with Parameters
function matchRoute(string $method, string $uri, array $routes)
{
    foreach ($routes as $route => $callback) {
        [$routeMethod, $routePattern] = explode(' ', $route, 2);

        if ($method !== $routeMethod) {
            continue;
        }

        $routePattern = url($routePattern);

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
