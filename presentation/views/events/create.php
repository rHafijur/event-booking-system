<?php 
    $pageTitle = "Create Event";
    require_once __DIR__.'/../layouts/header.php';
?>

    <!-- Event Creation Form -->
    <div class="container my-5">
        <h1 class="text-center mb-4">Create New Event</h1>
        <form id="eventForm" class="needs-validation" novalidate>

            <input type="hidden" name="_csrf_token" value="<?php echo $GLOBALS['csrf_token'] ?? ''; ?>">

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">Event Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                    <div class="invalid-feedback">Please enter the event name.</div>
                </div>
                <div class="col-md-6">
                    <label for="venue" class="form-label">Venue</label>
                    <input type="text" class="form-control" id="venue" name="venue" required>
                    <div class="invalid-feedback">Please enter the event venue.</div>
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                <div class="invalid-feedback">Please provide a description of the event.</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="capacity" class="form-label">Capacity</label>
                    <input type="number" class="form-control" id="capacity" name="capacity" min="1" required>
                    <div class="invalid-feedback">Please specify the event capacity.</div>
                </div>
                <div class="col-md-6">
                    <label for="ticket_price" class="form-label">Ticket Price ($)</label>
                    <input type="number" step="0.01" class="form-control" id="ticket_price" name="ticket_price" required>
                    <div class="invalid-feedback">Please specify the ticket price.</div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="event_date" class="form-label">Event Date</label>
                    <input type="date" class="form-control" id="event_date" name="event_date" required>
                    <div class="invalid-feedback">Please choose a valid event date.</div>
                </div>
                <div class="col-md-6">
                    <label for="booking_deadline" class="form-label">Booking Deadline</label>
                    <input type="datetime-local" class="form-control" id="booking_deadline" name="booking_deadline" required>
                    <div class="invalid-feedback">Please specify a booking deadline.</div>
                </div>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Event Image</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                <div class="invalid-feedback">Please upload an image for the event.</div>
            </div>


            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Create Event</button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('eventForm').addEventListener('submit', function (e) {
            e.preventDefault();

            if (!this.checkValidity()) {
                this.classList.add('was-validated');
                return;
            }

            const formData = new FormData(this);
            
            formData.set('booking_deadline', localTimeToUTC(formData.get('booking_deadline')));

            fetch('<?=url("/event/create")?>', {
                method: 'POST',
                body: formData,
            })
                .then(response => {
                    if (response.status === 200) {
                        showToast('Event created successfully!', 'success');
                        setTimeout(() => {
                            window.location.href = '<?=url("/events")?>';
                        }, 3000);
                    } else {
                        response.text().then(value => {
                            let error = JSON.parse(value);
                            for(let err of error.errors){
                                showToast(err, 'danger');
                            }
                        });
                    }
                })
                .catch(() => {
                    showToast("An error occurred. Please try again later.", "danger");
                });
        });
    </script>
<?php require_once __DIR__.'/../layouts/footer.php'; ?>
