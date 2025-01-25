<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">EventManager</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/dashboard">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/logout">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Events</h1>
            <a href="/event/create" class="btn btn-primary">Create New Event</a>
        </div>

        <!-- Filter/Search Form -->
        <form method="GET" action="/events" class="row g-3 mb-4">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search by name or venue" value="">
            </div>
            <div class="col-md-3">
                <select name="order_by" class="form-select">
                    <option value="">Sort By</option>
                    <option <?= ($_GET['order_by']??null) == 'created_at-ASC'? 'selected' : '' ?> value="created_at-ASC">Oldest</option>
                    <option <?= ($_GET['order_by']??null) == 'created_at-DESC'? 'selected' : '' ?> value="created_at-DESC">Latest</option>
                    <option <?= ($_GET['order_by']??null) == 'event_date-ASC'? 'selected' : '' ?> value="event_date-ASC">Event Date (Ascending)</option>
                    <option <?= ($_GET['order_by']??null) == 'event_date-DESC'? 'selected' : '' ?> value="event_date-DESC">Event Date (Descending)</option>
                    <option <?= ($_GET['order_by']??null) == 'capacity-ASC'? 'selected' : '' ?> value="capacity-ASC">Capacity (Ascending)</option>
                    <option <?= ($_GET['order_by']??null) == 'capacity-DESC'? 'selected' : '' ?> value="capacity-DESC">Capacity (Descending)</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-success w-100">Filter</button>
            </div>
            <div class="col-md-2">
                <a href="/events" class="btn btn-secondary w-100">Reset</a>
            </div>
        </form>

        <!-- Events Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Venue</th>
                        <th>Event Date</th>
                        <th>Capacity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                     <?php foreach($events as $index => $event): ?>
                        <tr>
                            <td><?= (($currentPage - 1) * $pageSize) + $index + 1 ?></td>
                            <td><?= htmlspecialchars($event->getName()) ?></td>
                            <td><?= htmlspecialchars($event->getVenue()) ?></td>
                            <td><?= $event->getEventDate()->format('d/M/Y') ?></td>
                            <td><?= $event->getCapacity() ?></td>
                            <td>
                                <a href="/event/<?= $event->getId() ?>" class="btn btn-sm btn-info">View</a>
                                <a href="/event/<?= $event->getId() ?>/edit" class="btn btn-sm btn-warning">Edit</a>
                                <form method="POST" action="/event/delete?id=1" class="d-inline">
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach ?>
                    <?php if(count($events) == 0): ?>
                        <tr>
                            <td colspan="6" class="text-center">No events found.</td>
                        </tr>
                    <?php endif ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?= renderPaginationWithQueryParams($currentPage, $totalPages, '/events', $_GET) ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
