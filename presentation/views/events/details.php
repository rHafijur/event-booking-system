<?php 
    $pageTitle = "Event Details";
    require_once __DIR__.'/../layouts/header.php';
?>

    <!-- Event Details Content -->
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Event Details</h1>
            <a href="<?=url("/events")?>" class="btn btn-secondary">Back to Events</a>
        </div>

        <!-- Event Information -->
        <div class="card mb-4">
            <div class="card-body">
                <h2 class="card-title">Event Name: <span class="text-primary"><?= htmlspecialchars($event->getName()) ?></span></h2>
                <p class="card-text"><strong>Venue:</strong> <?= htmlspecialchars($event->getVenue()) ?></p>
                <p class="card-text"><strong>Description:</strong> <?= htmlspecialchars($event->getDescription()) ?></p>
                <p class="card-text"><strong>Event Date:</strong> <?= $event->getEventDate()->format('d/M/Y') ?></p>
                <p class="card-text"><strong>Booking Deadline:</strong> <?= $event->getBookingDeadline()->format('d/M/Y') ?></p>
                <p class="card-text"><strong>Capacity:</strong> <?= htmlspecialchars($event->getCapacity()) ?></p>
                <p class="card-text"><strong>Ticket Price:</strong> $<?= htmlspecialchars($event->getTicketPrice()) ?></p>
                <!-- <p class="card-text"><strong>Organizer:</strong> John Doe</p> -->
                <div class="text-end">
                    <?php if(!$user->isAdmin()): ?>
                        <a href="<?=url("/event/{$event->getId()}/edit")?>" class="btn btn-warning">Edit Event</a>
                        <form onsubmit="deleteEvent(event)" method="POST" action="<?=url("/event/{$event->getId()}/delete")?>" class="d-inline">
                            <input type="hidden" name="_csrf_token" value="<?php echo $GLOBALS['csrf_token'] ?? ''; ?>">
                            <button type="submit" class="btn btn-danger">Delete Event</button>
                        </form>
                    <?php endif ?>
                    <a href="<?=url("/event/{$event->getId()}/download-attendees-report")?>" class="btn btn-info">Download Attendee List</a>
                </div>
            </div>
        </div>

        <!-- Attendee List -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4>Attendee List</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Registered At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($attendees as $index => $attendee): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= htmlspecialchars($attendee->getName()) ?> </td>
                                    <td><?= htmlspecialchars($attendee->getEmail()) ?></td>
                                    <td><?= $attendee->getRegisteredAt()->format('d/M/Y H:i:s') ?></td>
                                </tr>
                            <?php endforeach ?>
                            <?php if(count($attendees) == 0): ?>
                                <tr>
                                    <td colspan="5" class="text-center">No attendees registered yet.</td>
                                </tr>
                            <?php endif ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function deleteEvent(event){
            event.preventDefault();
            if(confirm('Are you really want to delete this event along with it\'s attendees')){
                event.target.submit();
            }
        }
    </script>
<?php require_once __DIR__.'/../layouts/footer.php'; ?>
