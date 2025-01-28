<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventManager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">EventManager</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if($user): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?=url("/dashboard")?>"><?= htmlspecialchars($user->getName()) ?></a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?=url("/login")?>">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?=url("/register")?>">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="bg-primary text-white text-center py-5">
        <div class="container">
            <h1>Welcome to EventManager</h1>
            <p class="lead">Effortlessly manage events and registrations with our simple tool.</p>
            <?php if($user == null): ?>
                <a href="<?=url("/register")?>" class="btn btn-light btn-lg mt-3">Get Started</a>
            <?php endif ?>
        </div>
    </header>

    <section class="my-5">
    <div class="container">
        <h1 class="text-center mb-4">Available Events</h1>
        <div class="row">
             <?php foreach($availableEvents as $event): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="<?= asset($event->getImage()) ?>" height="300px" class="card-img-top" alt="Event Image">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($event->getName()) ?></h5>
                            <p class="card-text"><strong>Venue:</strong> <?= htmlspecialchars($event->getVenue()) ?></p>
                            <p class="card-text"><strong>Date:</strong> <?= htmlspecialchars($event->getEventDate()->format('d/M/Y')) ?></p>
                            <p class="card-text"><strong>Deadline for booking:</strong> <?= htmlspecialchars($event->getBookingDeadline()->format('d/M/Y')) ?></p>
                            <p class="card-text"><strong>Registration Fee:</strong> $<?= $event->getTicketPrice() ?></p>
                            <?php if($event->availableTicketCount() !== null): ?>
                                <p class="card-text"><strong>Tickets Available:</strong> <?= $event->availableTicketCount() ?></p>
                            <?php endif ?>
                            <a href="<?=url("/event/{$event->getId()}/register")?>" class="btn btn-primary w-100">View Details & Register</a>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">Why Choose EventManager?</h2>
            <div class="row">
                <div class="col-md-4 text-center">
                    <div class="card border-0 shadow-sm p-4">
                        <i class="bi bi-person-check display-4 text-primary mb-3"></i>
                        <h5>User Authentication</h5>
                        <p>Secure login and registration with encrypted passwords.</p>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="card border-0 shadow-sm p-4">
                        <i class="bi bi-calendar-event display-4 text-primary mb-3"></i>
                        <h5>Event Management</h5>
                        <p>Create, update, and delete events effortlessly.</p>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="card border-0 shadow-sm p-4">
                        <i class="bi bi-file-earmark-spreadsheet display-4 text-primary mb-3"></i>
                        <h5>Event Reports</h5>
                        <p>Download attendee lists in CSV format for easy access.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-light text-center py-3">
        <div class="container">
            <p class="mb-0">&copy; 2025 EventManager. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
