<?php

require_once __DIR__.'/../infrastructure/database/migrations/CreateUsersTable.php';
require_once __DIR__.'/../infrastructure/database/migrations/CreateEventsTable.php';
require_once __DIR__.'/../infrastructure/database/migrations/CreateAttendeesTable.php';

CreateUsersTable::up();
CreateEventsTable::up();
CreateAttendeesTable::up();
