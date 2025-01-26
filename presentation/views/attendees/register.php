<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Registration</title>
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
            <a class="navbar-brand" href="/">EventManager</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container my-5">
        <div class="row">
            <!-- Event Details -->
            <div class="col-md-4">
                <div class="card">
                    <img src="/<?= $event->getImage() ?>" height="300" class="card-img-top img-fluid" alt="Event Image">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($event->getName()) ?></h5>
                        <p class="card-text"><strong>Description:</strong> <?= htmlspecialchars($event->getDescription()) ?></p>
                        <p class="card-text"><strong>Venue:</strong> <?= htmlspecialchars($event->getVenue()) ?></p>
                        <p class="card-text"><strong>Date:</strong> <?= htmlspecialchars($event->getEventDate()->format('d/M/Y')) ?></p>
                        <p class="card-text"><strong>Deadline for booking:</strong> <?= htmlspecialchars($event->getBookingDeadline()->format('d/M/Y')) ?></p>
                        <p class="card-text"><strong>Registration Fee:</strong> $<?= $event->getTicketPrice() ?></p>
                        <p class="card-text"><strong>Available Tickets:</strong> <?= $event->availableTicketCount() ?></p>
                    </div>
                </div>
            </div>

            <!-- Registration Form -->
            <div class="col-md-8">
                <div class="row d-flex justify-content-center">
                    <div class="col-md-8">
                        <h1 class="text-center mb-4">Register for the Event</h1>
                        <form id="event-registration-form" class="needs-validation" novalidate>

                            <input type="hidden" name="_csrf_token" value="<?php echo $GLOBALS['csrf_token'] ?? ''; ?>">
                            <input type="hidden" name="event_id" value="<?php echo $event->getId(); ?>">

                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                                <div class="invalid-feedback">Please enter your name.</div>
                            </div>
        
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                                <div class="invalid-feedback">Please provide a valid email address.</div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-success">Register</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toast-container"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Bootstrap validation
        (function () {
            'use strict';
            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();

        // AJAX Form Submission
        const form = document.getElementById('event-registration-form');
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            if (!form.checkValidity()) {
                return;
            }

            const formData = new FormData(form);

            fetch('/attendee/register', {
                method: 'POST',
                body: formData,
            })
            .then(response => {
                if (response.ok) {
                    showToast('Registration successful!', 'success');
                    setTimeout(() => {
                        window.location.href = '/';
                    }, 2000);
                } else {
                    response.text().then(value => {
                        let error = JSON.parse(value);
                        for(let err of error.errors){
                            showToast(err, 'danger');
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('An unexpected error occurred.', 'danger');
            });
        });

        // Show Toast
        function showToast(message, type) {
            const toastContainer = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = `toast align-items-center text-bg-${type} border-0 show`;
            toast.setAttribute('role', 'alert');
            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">
                        ${message}
                    </div>
                    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            `;
            toastContainer.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }
    </script>
</body>
</html>
