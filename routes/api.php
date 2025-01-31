<?php

use Infrastructure\Container;
use App\Factories\ControllerFactory;

$router['GET /api/events'] =  function (): void {
        $controller = ControllerFactory::getEventController();
        Container::getInstance()->call($controller, 'listForApi');
    };
