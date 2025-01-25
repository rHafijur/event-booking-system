<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
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
        <h1 class="text-center mb-4">Edit Event</h1>
        <form id="eventForm" class="needs-validation" novalidate>

            <input type="hidden" name="_csrf_token" value="<?php echo htmlspecialchars( $GLOBALS['csrf_token'] ?? ''); ?>">

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">Event Name</label>
                    <input type="text" class="form-control" value="<?= $event->getName() ?>" id="name" name="name" required>
                    <div class="invalid-feedback">Please enter the event name.</div>
                </div>
                <div class="col-md-6">
                    <label for="venue" class="form-label">Venue</label>
                    <input type="text" class="form-control" value="<?= $event->getVenue() ?>" id="venue" name="venue" required>
                    <div class="invalid-feedback">Please enter the event venue.</div>
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4" required><?= $event->getDescription() ?></textarea>
                <div class="invalid-feedback">Please provide a description of the event.</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="capacity" class="form-label">Capacity</label>
                    <input type="number" class="form-control" value="<?= $event->getCapacity() ?>" id="capacity" name="capacity" min="1" required>
                    <div class="invalid-feedback">Please specify the event capacity.</div>
                </div>
                <div class="col-md-6">
                    <label for="ticket_price" class="form-label">Ticket Price ($)</label>
                    <input type="number" step="0.01" class="form-control" value="<?= $event->getTicketPrice() ?>" id="ticket_price" name="ticket_price" required>
                    <div class="invalid-feedback">Please specify the ticket price.</div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="event_date" class="form-label">Event Date</label>
                    <input type="date" class="form-control" value="<?= $event->getEventDate()->format('Y-m-d') ?>" id="event_date" name="event_date" required>
                    <div class="invalid-feedback">Please choose a valid event date.</div>
                </div>
                <div class="col-md-6">
                    <label for="booking_deadline" class="form-label">Booking Deadline</label>
                    <input type="date" class="form-control" value="<?= $event->getBookingDeadline()->format('Y-m-d') ?>" id="booking_deadline" name="booking_deadline" required>
                    <div class="invalid-feedback">Please specify a booking deadline.</div>
                </div>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Event Image</label>
                <input type="file" class="form-control"  id="image" name="image" accept="image/*">
                <div class="form-text">Optional: Upload an image for the event.</div>
            </div>

            <input type="hidden" name="organizerId" value="[Organizer ID]">

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Create Event</button>
            </div>
        </form>
    </div>

    <!-- Toast Container -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="successToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    Event updated successfully!
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
        <div id="errorToast" class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="errorText">
                An error occurred. Please try again later.
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('eventForm').addEventListener('submit', function (e) {
            e.preventDefault();

            if (!this.checkValidity()) {
                this.classList.add('was-validated');
                return;
            }

            const formData = new FormData(this);
            fetch('/event/<?= $event->getId() ?>/update', {
                method: 'POST',
                body: formData,
            })
                .then(response => {
                    if (response.status === 200) {
                        const toast = new bootstrap.Toast(document.getElementById('successToast'));
                        toast.show();
                        setTimeout(() => {
                            window.location.href = '/events';
                        }, 2000);
                    } else {
                        response.text().then(value => {
                            let error = JSON.parse(value);
                            const errorText = error.errors.join('<br>');

                            document.getElementById('errorText').innerHTML =errorText;
                            const toast = new bootstrap.Toast(document.getElementById('errorToast'));
                            toast.show();
                        });
                    }
                })
                .catch(() => {
                    document.getElementById('errorText').innerHTML = "An error occurred. Please try again later.";
                    const toast = new bootstrap.Toast(document.getElementById('errorToast'));
                    toast.show();
                });
        });
    </script>
</body>
</html>
