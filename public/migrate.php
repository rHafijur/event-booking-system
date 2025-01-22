<?php

// require_once __DIR__.'/../infrastructure/database/migrations/CreateUsersTable.php';
// require_once __DIR__.'/../infrastructure/database/migrations/CreateEventsTable.php';
// require_once __DIR__.'/../infrastructure/database/migrations/CreateAttendeesTable.php';

require_once __DIR__ . '/../vendor/autoload.php';

use Infrastructure\Database\Migrations\CreateUsersTable, Infrastructure\Database\Migrations\CreateEventsTable, Infrastructure\Database\Migrations\CreateAttendeesTable;

CreateUsersTable::up();
CreateEventsTable::up();
CreateAttendeesTable::up();
