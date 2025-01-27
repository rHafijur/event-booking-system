<?php

use App\Factories\ControllerFactory;

$router['GET /api/events'] =  function (): void {
        $controller = ControllerFactory::getEventController();
        $controller->listForApi();
    };

    // var_dump($router);
    // exit;