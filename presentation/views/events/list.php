<?php 
    $pageTitle = "Event List";
    require_once __DIR__.'/../layouts/header.php';
?>

    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Events</h1>
            <?php if(!$user->isAdmin()): ?>
                <a href="/event/create" class="btn btn-primary">Create New Event</a>
            <?php endif ?>
        </div>

        <!-- Filter/Search Form -->
        <form method="GET" action="/events" class="row g-3 mb-4">
            <div class="col-md-4">
                <input type="text" value="<?= $_GET['search']?? '' ?>" name="search" class="form-control" placeholder="Search across the events and attendees" value="">
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
                                <?php if(!$user->isAdmin()): ?>
                                    <a href="/event/<?= $event->getId() ?>/edit" class="btn btn-sm btn-warning">Edit</a>
                                    <form onsubmit="deleteEvent(event)" method="POST" action="/event/<?= $event->getId() ?>/delete" class="d-inline">
                                        <input type="hidden" name="_csrf_token" value="<?php echo $GLOBALS['csrf_token'] ?? ''; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                <?php endif ?>
                                <a href="/event/<?= $event->getId() ?>/download-attendees-report" class="btn btn-sm btn-info">Download Attendee List</a>
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

    <script>
        function deleteEvent(event){
            event.preventDefault();
            if(confirm('Are you really want to delete this event along with it\'s attendees?')){
                event.target.submit();
            }
        }
    </script>

<?php require_once __DIR__.'/../layouts/footer.php'; ?>
