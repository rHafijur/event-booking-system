<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="<?=url("/dashboard")?>">EventManager</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?=url("/events")?>">
                            <?php if(!$user->isAdmin()): ?>
                                My 
                            <?php else: ?>
                                All
                            <?php endif ?>
                                Events
                        </a>
                    </li>
                    <?php if(!$user->isAdmin()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?=url("/event/create")?>">Create Event</a>
                        </li>
                    <?php endif ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?=url("/")?>">Landing Page</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?=url("/logout")?>">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <?php require_once __DIR__.'/flash_messages.php';